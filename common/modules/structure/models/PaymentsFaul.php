<?php

namespace common\modules\structure\models;

use Yii;
use common\modules\structure\models\Payment;
use common\modules\structure\models\PaymentsInvoices;
use common\modules\backoffice\models\Partners;
use common\components\advacash\models\Advacash;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payments_faul".
 *
 * @property integer $id
 * @property integer $matrix_id
 * @property integer $matrix_number
 * @property integer $partner_id
 * @property integer $paid_matrix_partner_id
 * @property integer $paid_matrix_id
 * @property integer $payment_type
 * @property string $amount
 * @property string $note
 * @property integer $status
 * @property integer $paid
 * @property integer $created_at
 *
 * @property Partners $paidMatrixPartner
 * @property Partners $partner
 */
class PaymentsFaul extends \yii\db\ActiveRecord
{
	public $login_receiver;
	public $login_paid;
	public $email;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments_faul';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matrix_id', 'matrix_number', 'partner_id', 'paid_matrix_partner_id', 'paid_matrix_id', 'payment_type', 'amount', 'note', 'staus', 'created_at'], 'required'],
            [['matrix_id', 'structure_number', 'matrix_number', 'partner_id', 'paid_matrix_partner_id', 'paid_matrix_id', 'payment_type', 'status', 'paid', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => Yii::t('form', 'ID'),
            'matrix_id' => Yii::t('form', 'ID Матрицы'),
            'structure_number' => Yii::t('form', 'Структура'),
            'matrix_number' => Yii::t('form', 'Номер матрицы'),
            'paid_matrix_id' => Yii::t('form', 'ID Матрицы отправителя'),
            'partner_id' => Yii::t('form', 'Partner ID'),
            'payment_type' => Yii::t('form', 'Платежная система'),
            'amount' => Yii::t('form', 'Сумма'),
            'note' => Yii::t('form', 'Сообщение'),
            'login_receiver' => Yii::t('form', 'Получатель'),
            'login_paid' => Yii::t('form', 'Отправитель'),
            'status' => Yii::t('form', 'Статус'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaidMatrixPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'paid_matrix_partner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
    
    public function getFaulsList($paid)
    {
		$paid = ($paid > 0) ? $paid : 0;
		$result = self::find()
		->select('`payments_faul`.`id`, `payments_faul`.`structure_number`, `payments_faul`.`matrix_number`, `payments_faul`.`matrix_id`, `payments_faul`.`amount`, `payments_faul`.`paid_matrix_id`,  
		`payments_faul`.`paid_matrix_partner_id`,  `payments_faul`.`partner_id`, `payments_faul`.`paid`, `payments_faul`.`note`, `payments_faul`.`status`, 
		`partners`.`login` AS `login_receiver`, `partners2`.`login` AS `login_paid`')
		->leftJoin('partners', '`partners`.`id` = `payments_faul`.`partner_id`')
		->leftJoin('partners partners2', '`partners2`.`id` = `payments_faul`.`paid_matrix_partner_id`')
		->where('`payments_faul`.`paid` = :paid', [':paid' => $paid])
		->orderBy('`payments_faul`.`created_at` DESC, `payments_faul`.`id` DESC');
		
		return $result;
	}
	
	public function getFaulBydID($id)
    {
		$result = self::find()
		->select('`payments_faul`.`id`, `payments_faul`.`structure_number`, `payments_faul`.`matrix_id`, `payments_faul`.`matrix_number`, `payments_faul`.`matrix_id`, 
		`payments_faul`.`amount`, `payments_faul`.`paid_matrix_id`, `payments_faul`.`note`, `payments_faul`.`created_at`, `payments_faul`.`status`,
		`payments_faul`.`paid`, `payments_faul`.`payment_type`, `partners`.`login` AS `login_receiver`, `partners2`.`login` AS `login_paid`')
		->leftJoin('partners', '`partners`.`id` = `payments_faul`.`partner_id`')
		->leftJoin('partners partners2', '`partners2`.`id` = `payments_faul`.`paid_matrix_partner_id`')
		->where('`payments_faul`.`id` = :id', [':id' => $id])
		->one();
		
		return $result;
	}
	
	public function getIDByLoginReceiver($login)
    {
		$result = self::find()
		->select('`payments_faul`.`partner_id`')
		->leftJoin('partners', '`partners`.`id` = `payments_faul`.`partner_id`')
		->where('`partners`.`login` = :login', [':login' => $login])
		->limit(1)
		->one();
		
		return $result;
	}
	
	public function getIDByLoginPaid($login)
    {
		$result = self::find()
		->select('`payments_faul`.`paid_matrix_partner_id`')
		->leftJoin('partners', '`partners`.`id` = `payments_faul`.`paid_matrix_partner_id`')
		->where('`partners`.`login` = :login', [':login' => $login])
		->limit(1)
		->one();
		
		return $result;
	}
	
	public function getFaulsWithPartnersList()
    {
		$result = self::find()
		->select('`payments_faul`.`id`, `payments_faul`.`partner_id`, `partners`.`email`')
		->leftJoin('partners', '`partners`.`id` = `payments_faul`.`partner_id`')
		->asArray()
		->all();
		
		return $result;
	}
	
	public function makePayment($model, $matricesSettings, $paymentFaul = false)
    {
		$result = false;
		
		if($model->matrix_number > 0 && $model->partner_id > 0 && $matricesSettings != null)
		{
			$paymentType = Payment::getPaymentType();
			$paymentModel = ($paymentType > 0) ? Payment::getPaymentModel($paymentType) : null;
			 					
			if($paymentType > 0 && $paymentModel != null && isset(Yii::$app->params['payments_types']) && (!empty(Yii::$app->params['payments_types'])))
			{	
				$note = Payment::getPaymentNote($model->paid_matrix_id, $model->paid_matrix_partner_id, $model->structure_number, $model->matrix_number, $model->partner_id, $matricesSettings['pay_off'], '', true);
				$paymentsType = Yii::$app->params['payments_types'];
				
				if(isset($paymentsType[$paymentType]))
				{
					$partnerData = Partners::find()->select($paymentsType[$paymentType][1])->where(['id'=>$model->partner_id])->asArray()->one();
					
					if(isset($partnerData[$paymentsType[$paymentType][1]]))
					{	
						$walletNumber = str_replace(' ', '', $partnerData[$paymentsType[$paymentType][1]]);
						
						if(Payment::checkWallet($walletNumber, $paymentsType[$paymentType][1], $paymentsType[$paymentType][5]))
						{	
							$response = [false, Yii::t('messages', 'Ошибка!'), ''];
							$paymentsModel = new Payment();
							$response = $paymentsModel->makePaymentByPaymentType($matricesSettings['pay_off'], $walletNumber, $paymentsType[$paymentType][1], $paymentModel, $note);	
							
							if(!$response[0])
							{	
								if($paymentFaul)
								{	
									if($this->insertPaymentFaul($model->partner_id, $model->structure_number, $model->matrix_number, $model->matrix_id, $model->paid_matrix_partner_id, $model->paid_matrix_id, $paymentType, $model->amount, $response[1]))
									{
										$result = true;
									}
								}
							}
							else
							{
								$paymentsInvoicesModel = new PaymentsInvoices();
								
								if($paymentsInvoicesModel->insertInvoice($model->matrix_id, $model->structure_number, $model->matrix_number, $model->partner_id, $model->paid_matrix_partner_id, $model->paid_matrix_id, $paymentType, 2, $model->amount, $response[2]))
								{
									$result = true;
								}
							}
						}
						else
						{	
							if($paymentFaul)
							{
								if($this->insertPaymentFaul($model->partner_id, $model->structure_number, $model->matrix_number, $model->matrix_id, $model->paid_matrix_partner_id, $model->paid_matrix_id, $paymentType, $model->amount, $response[1]))
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
	
	public function insertPaymentFaul($partnerID, $structureNumber, $matrixNumber, $matrixID, $paidMatrixPartnerID, $paidMatrixID, $paymentType, $amount, $note)
    {
		$this->partner_id = $partnerID;
		$this->structure_number = $structureNumber;
		$this->matrix_number = $matrixNumber;
		$this->matrix_id = $matrixID;
		$this->paid_matrix_partner_id = $paidMatrixPartnerID;
		$this->paid_matrix_id = $paidMatrixID;
		$this->payment_type = $paymentType;
		$this->amount = $amount;
		$this->note = $note;
		$this->created_at = time();
		
		return ($this->save(false)) ? true : false;
	}
	
	public function makeTotalPaymentInFauls()
    {
		$result = false;
		$counter = 0;
		$paymentsFaulList = PaymentsFaul::find()->where(['paid'=>0])->all();
		$paymentsList = PaymentsInvoices::find()->where(['account_type'=>2])->asArray()->all();
		
		if($paymentsFaulList !== null && !empty($paymentsList))
		{
			$paymentsList = ArrayHelper::index($paymentsList, 'paid_matrix_id');
			$paidIDList = [];
			
			foreach($paymentsFaulList as $i => $paymentData)
			{
				$result = true;
				$paid = false;
				
				if(isset($paymentsList[$paymentData->paid_matrix_id]))
				{	
					if($paymentData->matrix_number == $paymentsList[$paymentData->paid_matrix_id]['matrix_number'] && $paymentData->matrix_id == $paymentsList[$paymentData->paid_matrix_id]['matrix_id'])
					{
						$paid = true;
						$paidIDList[] = $paymentData->id;
					}
				}
				
				if(!$paid)
				{
					$matricesSettings = MatricesSettings::find()->where(['number'=>$paymentData->matrix_number])->one();
			
					if(($matricesSettings != null) && isset($matricesSettings->account_type))
					{
						if($matricesSettings->account_type = '2')
						{
							if((isset(Yii::$app->params['payments_types'])))
							{
								if(!$this->makePayment($paymentData, $matricesSettings))
								{
									$counter++;
									$result = false;
								}
								else
								{
									$paidIDList[] = $paymentData->id;
								}
							}
						}
					}
				}
			}
			
			if(!empty($paidIDList))
			{
				$paidIDList = array_flip($paidIDList);
				$result = \Yii::$app->db->createCommand()
				->update(self::tableName(), 
				[ 'paid'=>1], //columns and values
				['in', 'id', $paidIDList]) //condition, similar to where()
				->execute();
			}
		}
		
		return [$result, $counter];
	}
	
	public static function compareTransactions()
    {
		$file = Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.'advacash'.DIRECTORY_SEPARATOR.'transactions.csv';
	
		if($file != '')
		{
			self::parseTransactionsFile($file);
		}
		
		//print_r($parseData);
	}
	
	public static function parseTransactionsFile($file)
    {
		$matricesList = ArrayHelper::index(MatricesSettings::find()->asArray()->all(), 'number');
		
		/*echo '<pre>';
		print_r($matricesList);
		echo '</pre>';*/
		$csv = array_reverse(array_map('str_getcsv', file($file)));
		
		/*echo '<pre>';
		print_r($csv);
		echo '</pre>';*/
		
		foreach($csv as $i=>$data)
		{
			if(isset($data[13]))
			{
				echo $data[13].'<br/>';
				$explodedData = explode(",", $data[13]);
				
				echo '<pre>';
				print_r($explodedData);
				echo '</pre>';
				
				if((isset($explodedData[0]) && $explodedData[0] != 'withoutadmin.com') && (isset($explodedData[1])) && (isset($explodedData[2])))
				{
					$login = Advacash::getDataFromNotation($explodedData[0], '-', 1);
					$matrix = Advacash::getDataFromNotation($explodedData[1], ':', 1);
					$places = Advacash::getDataFromNotation($explodedData[2], ':', 1);
					$pay = (isset($matricesList[$matrix])) ? $matricesList[$matrix]['pay_off'] : 0;
					$levels = (isset($matricesList[$matrix])) ? $matricesList[$matrix]['levels'] : 0;
					echo $login.' - login'.'<br/>';
					echo $matrix.' - matrix'.'<br/>';
					echo $places.' - places'.'<br/>';
					echo $pay.' - pay'.'<br/>';
					echo $levels.' - levels'.'<br/>';
					
					for($j=($i+1); $j<=($i + $levels); $j++)
					{
						if(isset($csv[$j][13]))
						{
							echo $csv[$j][13].'<br/>';
							$payData = explode(",", $csv[$j][13]);
							
							if((isset($payData[0]) && $payData[0] == 'withoutadmin.com') && (isset($payData[1])) && (isset($payData[2])) && (isset($payData[3])) && (isset($payData[4])))
							{
								echo '<pre>';
								print_r($payData);
								echo '</pre>';
								
								$payLogin = Advacash::getDataFromNotation($payData[1], '-', 1);
								$payMatrix = Advacash::getPayMatrixFromNotation($payData[3]);
								
								echo $payLogin.' - login'.'<br/>';
								echo $payMatrix.' - matrix'.'<br/>';
								
								if(($login != $payLogin) && ($matrix != $payMatrix))
								{
									echo $data[13].' - bug!'.'<br/>';
								}
							}
						}
					}
				}
				
				echo '<hr>';
			}
		}
	}
}
