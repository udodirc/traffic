<?php
namespace common\modules\payeer\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\modules\payeer\models\Payeer;
use common\modules\payeer\models\CPayeer;
use common\modules\payeer\models\PayeerPayments;
use common\modules\structure\models\Payment;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\InvitePayOff;
use common\modules\backoffice\models\Partners;

/**
 * Payeer controller for the `payeer` module
 */
class PayeerController extends Controller
{
	/**
     * Switch off CSRF
    */
    public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false;
		 
		return parent::beforeAction($action); 
	}
	
	/**
     * Make pyment through paybox
     * @return mixed
     */
    public function actionMakePayment()
    {
		$params = Payeer::getSignatureParams();
		
		//Render page
		return $this->render('make_payment', [
			'params' => $params,
		]);
	}
	
	/**
     * Succes payment page through paybox
     * @return mixed
     */
    public function actionSuccessPayment()
    {
		$result = false;
		$payeerIPs = (isset(\Yii::$app->params['payeer']['payeer_ips'])) ? \Yii::$app->params['payeer']['payeer_ips'] : [];
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');	
		
		if(!empty($payeerIPs))
		{		
			if(!in_array($_SERVER['REMOTE_ADDR'], $payeerIPs))
			{	 
				$activationSession = \Yii::$app->session->get('activation');
				$sessionID = \Yii::$app->session->get('session_id');
				
				if(isset($activationSession[$sessionID]))
				{	 
					if(isset($activationSession[$sessionID]['m_desc']) 
					&&(isset($activationSession[$sessionID]['partner_id']) && $activationSession[$sessionID]['partner_id'] > 0)
					&& (isset($activationSession[$sessionID]['structure']) && $activationSession[$sessionID]['structure'] > 0)
					&& (isset($activationSession[$sessionID]['matrix']) && $activationSession[$sessionID]['matrix'] > 0)
					&& (isset($activationSession[$sessionID]['places']) && $activationSession[$sessionID]['places'] > 0)
					&& (isset($activationSession[$sessionID]['payment_type']) && $activationSession[$sessionID]['payment_type'] > 0)
					&& (isset($activationSession[$sessionID]['order_id']) && $activationSession[$sessionID]['order_id'] > 0)
					&& (isset($activationSession[$sessionID]['amount']) && $activationSession[$sessionID]['amount'] > 0))
					{	
						if(($activationSession[$sessionID]['m_desc'] == $_GET['m_desc']))
						{			
							$getParams = Yii::$app->request->get();
							$sign = Payeer::getSuccessPaymentSignature($getParams);
							
							if((isset($getParams['m_operation_id']) && $getParams['m_operation_id'] > 0) 
							&& (isset($getParams['m_operation_date']) && $getParams['m_operation_date'] != '')
							&& (isset($getParams['m_operation_pay_date']) && $getParams['m_operation_pay_date'] != '')
							&& (isset($getParams['m_shop']) && $getParams['m_shop'] > 0)
							&& (isset($getParams['m_orderid']) && $getParams['m_orderid'] > 0)
							&& (isset($getParams['m_amount']) && $getParams['m_amount'] > 0)
							&& (isset($getParams['m_curr']) && $getParams['m_curr'] != '')
							&& (isset($getParams['m_sign']) && $getParams['m_sign'] != '')
							&& (isset($getParams['m_status']) && $getParams['m_status'] != ''))
							{	
								if($getParams['m_sign'] == $sign && $getParams['m_status'] == 'success')
								{	
									$model = new Matrix();
									$partnerID = $activationSession[$sessionID]['partner_id'];
									$partner = Partners::find()->select(['sponsor_id', 'matrix_1', 'status'])->where(['id'=>$partnerID])->one();
									
									if($partner->matrix_1 > 0)
									{	
										$result = $model->reservePlacesInStructure($partner->sponsor_id, $partnerID, $activationSession[$sessionID]['structure'], $activationSession[$sessionID]['matrix'], $partner->status, $activationSession[$sessionID]['places'], '', 0, '', false);
									}
									else
									{	
										$result = $model->addPartnerInStructure($partner->sponsor_id, $partnerID, 1, 1, '', false, 2, false, false, false);
									}
									
									if($result)
									{	
										$demo = false;
										$matrixID = Matrix::getLastMatrixIDByPartnerID($activationSession[$sessionID]['structure'], $activationSession[$sessionID]['matrix'], $partnerID, $demo);
										
										if($matrixID > 0)
										{
											$result = PayeerPayments::insertPayments($partnerID, $activationSession[$sessionID]['structure'], $activationSession[$sessionID]['matrix'], $matrixID, $activationSession[$sessionID]['places'], $getParams['m_orderid'], 1, $getParams['m_amount'], $getParams['m_curr'], $getParams['m_operation_id'], strtotime($getParams['m_operation_date']), strtotime($getParams['m_operation_pay_date']));
										}
										
										if($result)
										{
											if(\Yii::$app->session->get('activation'))
											{
												\Yii::$app->session->remove('activation');
											}
											
											$class = 'success';
											$msg = Yii::t('messages', 'Оплата прошла успешна! Перейдите в раздел ваш заработок!');
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
		return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/activation');
	}
	
	public function actionMakePayOff($id, $type)
	{
		$result = false;
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
		if($id > 0 && $type > 0)
		{	
			$paymentData = Payment::getPaymentData($id, $type, 'payeer_wallet');
			
			if($paymentData !== null)
			{	
				if($paymentData['login'] != '' 
					&& $paymentData['wallet']  != ''
					&& $paymentData['id'] > 0
					&& $paymentData['partner_id'] > 0 
					&& $paymentData['structure_number'] > 0 
					&& $paymentData['matrix_number'] > 0 
					&& $paymentData['matrix_id'] > 0
					&& $paymentData['amount'] > 0
				)
				{	echo 'Work!';
					$result = Payeer::makeAutoPayment($paymentData['login'], $paymentData['id'], $paymentData['partner_id'], $paymentData['structure_number'], $paymentData['matrix_number'], $paymentData['matrix_id'], $type, $paymentData['amount'], $paymentData['wallet']);
					var_dump($result);
					if($result)
					{
						$class = 'success';
						$msg = Yii::t('messages', 'Вылата прошла успешна!');
					}
				}
				else
				{	echo 'Work2!';
					if($paymentData['payeer_wallet'] == '')
					{
						$msg = Yii::t('messages', 'У партнера нет кошелька!');
					}
					elseif($paymentData['amount'] <= 0)
					{
						$msg = Yii::t('messages', 'Сумма меньше 0!');
					}
				}
			}
		}
		
		$url = Payment::getAutoPaymentUrl($type);
		echo $msg;
		/*\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
		return $this->redirect(\Yii::$app->request->BaseUrl.$url);*/
	}
	
	public function actionTest()
	{
		$params = (isset(\Yii::$app->params['payeer'])) ? \Yii::$app->params['payeer'] : [];
					
			if(!empty($params))
			{	echo 'Work!';
				if(
					(isset($params['api_id']) && $params['api_id'] > 0) 
					&& (isset($params['account_number']) && $params['account_number'] != '') 
					&& (isset($params['api_ip']) && $params['api_ip'] != '')
					&& (isset($params['currency']) && $params['currency'] != '')
					&& (isset($params['api_secret_key']) && $params['api_secret_key'] != '')
				)
				{		
					$payeer = new CPayeer($params['account_number'], $params['api_id'], $params['api_secret_key']);
					echo 'Work2!';
					if ($payeer->isAuth())
					{
						echo "You are successfully authorized";
						
						$arPost['account'] = $params['account_number'];
						$arPost['apiId'] = $params['api_ip'];
						$arPost['apiPass'] = $params['api_secret_key'];
						$arPost['action'] = 'checkUser';
						$arPost['user'] = 'P1006191384';
						
						$result = $payeer->checkUser($arPost);
						
						echo '<pre>';
						var_dump($result);
						echo '</pre>';
						
						if($result)
						{
							echo '<pre>';
							print_r($arPost);
							echo '</pre>';
							
							$arPost['account'] = $params['account_number'];
							$arPost['apiId'] = $params['api_ip'];
							$arPost['apiPass'] = $params['api_secret_key'];
							$arPost['action'] = 'transfer';
							$arPost['curIn'] = $params['currency'];
							$arPost['sum'] = '1.00';
							$arPost['curOut'] = $params['currency'];
							$arPost['sumOut'] = '1.00';
							$arPost['to'] = 'P1006191384';
							$arPost['comment'] = 'Перевод #1365';
							
							$result = $payeer->transfer($arPost);
						
							echo '<pre>';
							var_dump($result);
							echo '</pre>';
						}
					}
					else
					{
						echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
					}
				}
			}
	}
}
