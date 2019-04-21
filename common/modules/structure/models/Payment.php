<?php

namespace common\modules\structure\models;

use Yii;
use common\modules\structure\models\MatricesSettings;
use common\modules\structure\models\PaymentsFaul;
use common\modules\structure\models\PaymentsInvoices;
use common\modules\structure\models\Matrix;
use common\components\advacash\models\Advacash;
use common\modules\payeer\models\PayeerPayments;
use common\modules\backoffice\models\Partners;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\InvitePayOff;
use common\modules\structure\models\AutoPayOffLogs;
use common\models\Service;

/**
 * This is the model class.
 */
class Payment extends \yii\db\ActiveRecord
{
	const WALLET_IS_EMPTY = 'Кошелек отсутствует';
	
	public function activatePaymentByAccounting($structureNumber, $matrixNumber, $partnerID, $matricesSettings)
    {
		$result = false;
		
		$paymentType = self::getPaymentType();
		$paymentModel = ($paymentType > 0) ? self::getPaymentModel($paymentType) : null;
		$matrixData = Matrix::getDataFromMatrixByPartnerID($structureNumber, $matrixNumber, $partnerID, false);
			
		if($matrixData !== null && $matrixData['id'] > 0 && $paymentType > 0 && $paymentModel != null)
		{	
			if($this->makePaymentByAccounting($structureNumber, $matrixData['id'], $partnerID, $matrixNumber, $paymentType, $paymentModel, $matricesSettings))
			{	
				$result = true;
			}
		}
		
		return $result;
	}
	
	public function makePaymentByAccounting($structureNumber, $id, $partnerID, $number, $paymentType, $paymentModel, $matricesSettings, $goldToken = false)
    {
		$result = false;
		$faultArray = [];
		
		if(isset(Yii::$app->params['payments_types']) && (!empty(Yii::$app->params['payments_types'])) && !empty($matricesSettings))
		{
			$paymentsType = Yii::$app->params['payments_types'];
			
			if(isset($paymentsType[$paymentType]))
			{
				$data = Matrix::getMatrixPaymentDataBySponsorID($structureNumber, $number, $id, $matricesSettings['levels']);
				
				if(!empty($data))
				{
					$successArray = [];
					
					if($paymentModel != null)
					{
						$paymentsInvoiceID = PaymentsInvoices::find()->select('id')->orderBy('id DESC')->limit(1)->one();
						$paymentsInvoiceID = ($paymentsInvoiceID !== null) ? $paymentsInvoiceID->id : 0;
						
						foreach($data as $key=>$paymentData)
						{
							$invoiceResult = false;
							
							if(isset($paymentData[$paymentsType[$paymentType][1]]) && isset($paymentsType[$paymentType][1]) && isset($paymentsType[$paymentType][5]))
							{	
								if(self::checkWallet($paymentData[$paymentsType[$paymentType][1]], $paymentsType[$paymentType][1], $paymentsType[$paymentType][5]))
								{	
									$note = self::getPaymentNote($id, $paymentData['partner_id'], $structureNumber, $number, $partnerID, $matricesSettings['pay_off'], $paymentData['login'], false, $goldToken);
									$response = [false, Yii::t('messages', 'Ошибка!'), ''];
									$response = $this->makePaymentByPaymentType($matricesSettings['pay_off'], $paymentData[$paymentsType[$paymentType][1]], $paymentsType[$paymentType][1], $paymentModel, $note);
									
									if(!isset($response[0]) || !($response[0]))
									{
										$faultArray[$key][] = $paymentData['id'];
										$faultArray[$key][] = $structureNumber;
										$faultArray[$key][] = $number;
										$faultArray[$key][] = $paymentData['partner_id'];
										$faultArray[$key][] = $id;
										$faultArray[$key][] = $partnerID;
										$faultArray[$key][] = $paymentType;
										$faultArray[$key][] = $matricesSettings['pay_off'];
										$faultArray[$key][] = $response[1];
										$faultArray[$key][] = time();
									}
									else
									{
										if($paymentsInvoiceID > 0)
										{
											$invoiceResult = true;
											$paymentsInvoiceID++;
											$successArray[$key][] = $paymentData['partner_id'];
											$successArray[$key][] = $structureNumber;
											$successArray[$key][] = $number;
											$successArray[$key][] = $paymentData['id'];
											$successArray[$key][] = $partnerID;
											$successArray[$key][] = $id;
											$successArray[$key][] = $paymentType;
											$successArray[$key][] = 2;
											$successArray[$key][] = $matricesSettings['pay_off'];
											$successArray[$key][] = $paymentsInvoiceID;
											$successArray[$key][] = $response[2];
											$successArray[$key][] = time();
										}
									}
								}
								else
								{
									$faultArray[$key][] = $paymentData['id'];
									$faultArray[$key][] = $structureNumber;
									$faultArray[$key][] = $number;
									$faultArray[$key][] = $paymentData['partner_id'];
									$faultArray[$key][] = $id;
									$faultArray[$key][] = $partnerID;
									$faultArray[$key][] = $paymentType;
									$faultArray[$key][] = $matricesSettings['pay_off'];
									$faultArray[$key][] = self::getPaymentByAccountingNotes(1);
									$faultArray[$key][] = time();
								}
							}
							else
							{
								$faultArray[$key][] = $paymentData['id'];
								$faultArray[$key][] = $structureNumber;
								$faultArray[$key][] = $number;
								$faultArray[$key][] = $paymentData['partner_id'];
								$faultArray[$key][] = $id;
								$faultArray[$key][] = $partnerID;
								$faultArray[$key][] = $paymentType;
								$faultArray[$key][] = $matricesSettings['pay_off'];
								$faultArray[$key][] = self::getPaymentByAccountingNotes(1);
								$faultArray[$key][] = time();
							}
						}
					}
					
					if(!empty($faultArray))
					{	
						\Yii::$app->db->createCommand()->batchInsert('payments_faul', ['matrix_id', 'structure_number', 'matrix_number', 'partner_id', 'paid_matrix_id', 'paid_matrix_partner_id', 'payment_type', 'amount', 'note', 'created_at'], $faultArray)->execute();
					}
					
					if(!empty($successArray))
					{	
						\Yii::$app->db->createCommand()->batchInsert('payments_invoices', ['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'paid_matrix_partner_id', 'paid_matrix_id', 'payment_type', 'account_type', 'amount', 'order_id', 'transact_id', 'created_at'], $successArray)->execute();
					}
					
					$result = $invoiceResult;
				}
			}
		}
		
		return [$result, $faultArray];
	}
	
	public function makePaymentByPaymentType($amount, $walletNumber, $paymentType, $paymentModel, $note)
    {
		$result = [false, Yii::t('messages', 'Ошибка!'), ''];
		
		if($paymentType == 'advcash_wallet')
		{	
			$result = $paymentModel->makePayment($amount, $walletNumber, $note);
		}
		
		return $result;
	}
	
	public function makePaymentInvoice($matrixID, $matrixNumber, $partnerID, $paymentType, $amount, $transactID)
    {
		$result = false;
		
		if($matrixID > 0 && $matrixNumber > 0 && $partnerID > 0 && $paymentType > 0 && $amount > 0 && $transactID != '')
		{	
			$paymentsInvoicesModel = new PaymentsInvoices();
								
			if(!$paymentsInvoicesModel->insertInvoice($matrixID, $matrixNumber, $partnerID, $partnerID, $matrixID, $paymentType, 1, $amount, $transactID))
			{	
				$model = new PaymentsFaul();
								
				$model->partner_id = $partnerID;
				$model->matrix_number = $matrixNumber;
				$model->matrix_id = 0;
				$model->paid_matrix_partner_id = $partnerID;
				$model->paid_matrix_id = 0;
				$model->payment_type = $paymentType;
				$model->amount = $amount;
				$model->note = Yii::t('messages', 'Ошибка платежа!');
				$model->created_at = time();
				$model->save(false);		
			}
			else
			{
				$result = true;
			}
		}
		
		return $result;
	}
	
	public function makePayment($partnerID, $matrixID, $structureNumber, $matrixNumber, $paymentType, $matricesSettings, $amount, $paymentFaul = false, $paymentsInvoice = false, $goldToken = false)
    {
		$result = false;
		
		if($partnerID > 0 && $paymentType > 0 && !empty($matricesSettings) && (isset(Yii::$app->params['payments_types']) && (!empty(Yii::$app->params['payments_types']))))
		{
			$paymentsType = Yii::$app->params['payments_types'];
									
			if(isset($paymentsType[$paymentType]) && isset($paymentsType[$paymentType][1]) && isset($paymentsType[$paymentType][5]))
			{
				$partnerData = Partners::find()->select([$paymentsType[$paymentType][1], 'login'])->where(['id'=>$partnerID])->asArray()->one();
					
				if(isset($partnerData[$paymentsType[$paymentType][1]]))
				{	
					$walletNumber = str_replace(' ', '', $partnerData[$paymentsType[$paymentType][1]]);
							
					if(self::checkWallet($walletNumber, $paymentsType[$paymentType][1], $paymentsType[$paymentType][5]))
					{
						$response = [false, Yii::t('messages', 'Ошибка!'), ''];
						$paymentsModel = new Payment();
						$paymentModel = ($paymentType > 0) ? self::getPaymentModel($paymentType) : null;
						$note = self::getPaymentNote($matrixID, $partnerID, $structureNumber, $matrixNumber, $partnerID, $amount, $partnerData['login'], false, $goldToken);
						$amount = ($goldToken) ? $amount : $matricesSettings['pay_off'];
						$response = $paymentsModel->makePaymentByPaymentType($amount, $walletNumber, $paymentsType[$paymentType][1], $paymentModel, $note);	
						
						if(!$response[0])
						{	
							if($paymentFaul)
							{	
								$paymentsFaulModel = new PaymentsFaul();
								
								if($paymentsFaulModel->insertPaymentFaul($partnerID, $structureNumber, $matrixNumber, $matrixID, $partnerID, $matrixID, $paymentType, $amount, $response[1]))
								{	
									$result = true;
								}
							}
						}
						else
						{
							if($paymentsInvoice)
							{	
								$paymentsInvoicesModel = new PaymentsInvoices();
									
								if($paymentsInvoicesModel->insertInvoice($matrixID, $structureNumber, $matrixNumber, $partnerID, $partnerID, $matrixID, $paymentType, 2, $amount, $response[2]))
								{	
									$result = true;
								}
							}
						}
					}
				}
			}	
		}
		
		return $result;
	}
	
	public static function getPaymentByAccountingNotes($number)
	{
		$result = [
			1 => self::WALLET_IS_EMPTY,
		];
		
		return (isset($result[$number])) ? Yii::t('form', $result[$number]) : '';
	}
	
	public static function getPaymentModel($paymentType)
	{
		$result = null;
		
		if(isset(\Yii::$app->params['payments_types']) && isset(\Yii::$app->params['payments_types'][$paymentType][3]) && \Yii::$app->params['payments_types'][$paymentType][3] !== null)
		{
			$className = \Yii::$app->params['payments_types'][$paymentType][3];
		
			if(class_exists($className))
			{
				$dataModel = new $className();
				$result = new $className();
			}
		}
		
		return $result;
	}
	
	public static function getPaymentNote($matrixID, $parentID, $structureNumber, $matrixNumber, $partnerID, $amount, $sponsorLogin = '', $paymenFaul = false, $goldToken = false)
	{
		$result = '';
		
		if($matrixNumber > 0 && $partnerID > 0 && $parentID > 0 && $amount > 0)
		{
			$site = (isset(\Yii::$app->params['site_url'])) ? \Yii::$app->params['site_url'] : '';
			
			$model = Partners::findOne($partnerID);
			
			if($sponsorLogin == '')
			{
				$sponsorLogin = (Partners::findOne($parentID) !== null) ? Partners::findOne($parentID)->login : '';
			}
			
			if(($model !== null) && ($sponsorLogin != ''))
			{
				$partnersName = ($paymenFaul) ? ', from - '.$sponsorLogin.', to - '.$model->login : ', from - '.$model->login.', to - '.$sponsorLogin;
				$result = $site.$partnersName.', Structure-'.$structureNumber.', Matrix-'.$matrixNumber.' (ID - '.$matrixID.'), '.(($goldToken) ? 'GoldJeton ' : '').'$'.$amount;
			}
		}
		
		return $result;
	}
	
	public function activateByPaymentSystem($id, $structureNumber, $matrix, $places, $reserve = false, $paymentType, $amount, $transactID)
    {
		$result = false;
		
		if(($model = Partners::findOne($id)) !== null) 
        {	
			$matixModel = new Matrix();
			$sponsorID = ($reserve) ? $id : $model->sponsor_id;
			$status = ($model->status > 2) ? $model->status : 2;
			$result = $matixModel->reservePlacesInStructure($sponsorID, $id, $structureNumber, $matrix, $status, $places, $paymentType, $amount, $transactID);
		} 
		
		return $result;
	}
	
	public static function getPaymentType()
    {
		if(isset(Yii::$app->params['payments_types']))
		{
			$paymentTypes = Yii::$app->params['payments_types'];
			$result = array_flip(array_combine(array_keys($paymentTypes), array_column($paymentTypes, 4)));
		}
		
		return (isset($result['default'])) ? $result['default'] : 0;
	}
	
	public static function checkWallet($walletNumber, $paymentType, $currency)
    {
		$result = false;
		
		switch($paymentType)
		{
			case 'advcash_wallet':
			
			if($walletNumber != '')
			{	
				$walletNumber = trim($walletNumber);
				$walletCurrency = mb_substr($walletNumber, 0, 1, 'UTF-8');
				
				if(strcasecmp($walletCurrency, $currency) == 0) 
				{
					$number = mb_substr($walletNumber, 1, mb_strlen($walletNumber, 'UTF-8'), 'UTF-8');
					$number = str_replace(' ', '', $number);

					if((mb_strlen($number, 'UTF-8') == '12') && (is_numeric($number)))
					{	
						$result = true;
					}
				}
			}
			
			break;
				
			default:
			$result = false;
			break;
		}
		
		return $result;
	}
	
	public static function isPaymentAllowed()
    {
		return (isset(\Yii::$app->params['is_payment_allowed'])) ? \Yii::$app->params['is_payment_allowed'] : false;
	}
	
	public static function getInvoiceID()
    {
		$result = 0;
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes))
		{
			$paymentType = self::getPaymentType();
			
			if(isset($paymentsTypes[$paymentType][1]))
			{
				switch($paymentsTypes[$paymentType][1])
				{
					case 'payeer_wallet':
					
						$paymentsInvoiceID = PayeerPayments::find()->select('order_id')->orderBy('id DESC')->limit(1)->one();
						$result = ($paymentsInvoiceID !== null) ? $paymentsInvoiceID->order_id + 1 : 1;
					
					break;
						
					default:
	
						$paymentsInvoiceID = PaymentsInvoices::find()->select('id')->orderBy('id DESC')->limit(1)->one();
						$result = ($paymentsInvoiceID !== null) ? $paymentsInvoiceID->id + 1 : 1;
						
					break;
				}
			}
		}
		
		return $result;
	}
	
	public static function getPaymentData($id, $type, $wallet)
	{
		$result = null;
		
		if($id > 0 && $type > 0 && $wallet != '')
		{
			$table = '';
			
			switch($type)
			{
				case 1:
						
					$result = MatrixPayments::getPaymentData($id, $wallet);
						
				break;
				
				case 2:
						
					$result = InvitePayOff::getPaymentData($id, $wallet);
						
				break;
				
				case 3:
						
					$result = AutoPayOffLogs::getPaymentData($id, $wallet);
						
				break;
							
				default:
		
					$result = null;
							
				break;
			}
			//var_dump($result);
			/*if($table != '')
			{
				$sql = "SELECT `".$table."`.*, `partners`.`login` AS `login`, `benefit_partner`.`".$wallet."` AS `benefit_partner_wallet`
				FROM `".$table."`
				LEFT JOIN `partners` ON `partners`.`id` = `".$table."`.`partner_id`
				LEFT JOIN `partners` AS `benefit_partner` ON `benefit_partner`.`id` = `".$table."`.`benefit_partner_id`
				WHERE `".$table."`.`id`='".$id."'
				ORDER BY `id` DESC
				LIMIT 0,1";
				echo $sql;
				$connection = \Yii::$app->db;
				$result = $connection->createCommand($sql)->queryOne();
			}*/
		}
		
		return $result;
	}
	
	public static function getAutoPaymentUrl($type)
	{
		$result = '/accounting/backend-accounting';
		
		switch($type)
		{
			case 1:
						
				$result = '/matrix-payments-list';
						
			break;
				
			case 2:
						
				$result = '/invite-payoff-list';
						
			break;
				
			case 3:
						
				$result = '/invite-payoff-list';
						
			break;
							
			default:
		
				$result = '/accounting/backend-accounting';
							
			break;
		}
		
		return $result;
	}
}
