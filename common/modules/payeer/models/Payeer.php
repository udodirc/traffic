<?php
namespace common\modules\payeer\models;

use Yii;
use common\models\DbBase;
use common\modules\structure\model\Payment;
use common\modules\payeer\models\CPayeer;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\InvitePayOff;
use common\modules\structure\models\AutoPayOffLogs;

class Payeer
{
	public static function getSignatureParams()
    {
		$result = [];
		
		$activationSession = \Yii::$app->session->get('activation');
		$sessionID = \Yii::$app->session->get('session_id');
		
		if(
			(isset($activationSession[$sessionID]['partner_id']) && $activationSession[$sessionID]['partner_id'] > 0)
			&& (isset($activationSession[$sessionID]['structure']) && $activationSession[$sessionID]['structure'] > 0)
			&& (isset($activationSession[$sessionID]['matrix']) && $activationSession[$sessionID]['matrix'] > 0)
			&& (isset($activationSession[$sessionID]['places']) && $activationSession[$sessionID]['places'] > 0)
			&& (isset($activationSession[$sessionID]['payment_type']) && $activationSession[$sessionID]['payment_type'] > 0)
			&& (isset($activationSession[$sessionID]['order_id']) && $activationSession[$sessionID]['order_id'] > 0)
			&& (isset($activationSession[$sessionID]['amount']) && $activationSession[$sessionID]['amount'] > 0)
		)
		{
			$params = (isset(\Yii::$app->params['payeer'])) ? \Yii::$app->params['payeer'] : [];
					
			if(!empty($params))
			{
				if(
					(isset($params['merchant_id']) && $params['merchant_id'] > 0) 
					&& (isset($params['currency']) && $params['currency'] != '') 
					&& (isset($params['success_url']) && $params['success_url'] != '')
					&& (isset($params['fail_url']) && $params['fail_url'] != '')
					&& (isset($params['status_url']) && $params['status_url'] != '')
					&& (isset($params['secret_key']) && $params['secret_key'] != '')
					&& (isset($params['cipher_key']) && $params['cipher_key'] != '')
				)
				{
					$note = self::getPaymentNote(Yii::$app->user->identity->login, $activationSession[$sessionID]['structure'], $activationSession[$sessionID]['matrix'], $activationSession[$sessionID]['places'], $activationSession[$sessionID]['amount']);
					
					$description = base64_encode($note);
					$orderID = $activationSession[$sessionID]['order_id'];
					$amount = number_format($activationSession[$sessionID]['amount'], 2, '.', '');
					$secretKey = $params['secret_key'];
					$cipherKey = $params['cipher_key'];
					$cipherKey = md5($params['cipher_key'].$activationSession[$sessionID]['order_id']);
					$additionalParams = [
						'success_url' => $params['success_url'],
						'fail_url' => $params['fail_url'],
						'status_url' => $params['status_url'],
					];
					/*$mParams = urlencode(base64_encode(openssl_encrypt(json_encode($additionalParams),
					'AES-256-CBC', $cipherKey, OPENSSL_RAW_DATA)));
					
					/*$mParams = urlencode(base64_encode(mcrypt_encrypt(MCR YPT_RIJNDAEL_256,
					$cipherKey, json_encode($additionalParams), MCRYPT_MODE_ECB)));*/
					
					$paramsArr = [
						$params['merchant_id'],
						$orderID,
						$amount,
						$params['currency'],
						$description,
						$secretKey
					];
					$sign = strtoupper(hash('sha256', implode(':', $paramsArr)));
					
					$activationSession[$sessionID]['m_sign'] = $sign;
					$activationSession[$sessionID]['m_desc'] = $description;
				
					\Yii::$app->session->set('activation', $activationSession);
					
					$result = [
						'm_shop' => $params['merchant_id'],
						'm_orderid' => $orderID,
						'm_amount' => $amount,
						'm_curr' => $params['currency'],
						'm_desc' => $description,
						'm_sign' => $sign,
						'form[ps]' => 2609,
						'form[curr[2609]]' => $params['currency'],
						'm_cipher_method' => 'AES-256-CBC',
					];
				}
			}
		}
		
		return $result;
	}
	
	public static function getSuccessPaymentSignature($getParams)
    {
		$result = '';
		$params = (isset(\Yii::$app->params['payeer'])) ? \Yii::$app->params['payeer'] : [];
					
		if(!empty($params))
		{
			if((isset($params['secret_key']) && $params['secret_key'] != ''))
			{
				$arHash = [
					$getParams['m_operation_id'],
					$getParams['m_operation_ps'],
					$getParams['m_operation_date'],
					$getParams['m_operation_pay_date'],
					$getParams['m_shop'],
					$getParams['m_orderid'],
					$getParams['m_amount'],
					$getParams['m_curr'],
					$getParams['m_desc'],
					$getParams['m_status'],
					$params['secret_key']
				];
				
				$result = strtoupper(hash('sha256', implode(':', $arHash)));
			}
		}
		
		return $result;
	}
	
	public static function getPaymentNote($login, $structureNumber, $matrixNumber, $places, $amount, $payOff = false, $matrixID = 0, $type = 0)
	{
		$result = '';
		$site = (isset(\Yii::$app->params['site_url'])) ? \Yii::$app->params['site_url'] : '';
		
		if($payOff)
		{
			if($matrixNumber > 0 && $structureNumber > 0 && $matrixID > 0 && $amount > 0)
			{
				$result = $site.': '.Yii::t('form', 'Выплата за').' '.(($type == 1) ? Yii::t('form', 'матрицу') : Yii::t('form', 'личное приглашение')).' ('.Yii::t('form', 'Login').' - '.$login.', '.Yii::t('form', 'Структура').' - '.$structureNumber.', '.Yii::t('form', 'Матрица').' - '.$matrixNumber.', '.Yii::t('form', 'ID Матрицы').' - '.$matrixID.', '.Yii::t('form', 'Сумма').' - $'.$amount.')';
			}
		}
		else
		{
			if($matrixNumber > 0 && $structureNumber > 0 && $login != '' && $amount > 0)
			{
				$result = $site.': from - '.$login.', '.Yii::t('form', 'Структура').' - '.$structureNumber.', '.Yii::t('form', 'Матрица').' - '.$matrixNumber.', '.Yii::t('form', 'Мест').' - '.$places.', '.Yii::t('form', 'Сумма').' - $'.$amount;
			}
		}
		
		return $result;
	}
	
	public static function makePayment($amount, $walletNumber, $note)
    {
		$result = false;
		$msg = Yii::t('messages', 'Ошибка!');
		$transactionID = 0;
		$currency = '';
		$params = (isset(\Yii::$app->params['payeer'])) ? \Yii::$app->params['payeer'] : [];
					
		if(!empty($params))
		{
			if(
				(isset($params['api_id']) && $params['api_id'] > 0) 
				&& (isset($params['account_number']) && $params['account_number'] != '') 
				&& (isset($params['api_ip']) && $params['api_ip'] != '')
				&& (isset($params['currency']) && $params['currency'] != '')
				&& (isset($params['api_secret_key']) && $params['api_secret_key'] != '')
			)
			{
				$payeer = new CPayeer($params['account_number'], $params['api_id'], $params['api_secret_key']);
				$currency = $params['currency'];
				
				if($payeer->isAuth())
				{	
					if(self::checkUser($params['account_number'], $params['api_id'], $params['api_secret_key'], $walletNumber, $amount, $params['currency'], $payeer))
					{
						$transferResult = self::transfer($params['account_number'], $params['api_id'], $params['api_secret_key'], $walletNumber, $params['currency'], $amount, $note, $payeer);
						$transactionID = (isset($transferResult['historyId'])) ? $transferResult['historyId'] : 0;
						
						if($transactionID > 0)
						{
							$result = true;
							$msg = Yii::t('messages', 'Оплата совершенна успешна!');
						}
						else
						{
							$msg = Yii::t('messages', 'Оплата не была совершенна!');
						}
					}
					else
					{
						$msg = Yii::t('messages', 'Такого аккаунта в Payeer нет!');
					}
				}
			}
		}
		
		return [$result, $msg, $transactionID, $currency];
	}
	
	public static function makeAutoPayment($login, $id, $partnerID, $structureNumber, $matrixNumber, $matrixID, $type, $amount, $walletNumber, $auto = false)
    {
		$result = false;
		
		if($structureNumber > 0 && $matrixNumber > 0 && $matrixID > 0 && $type > 0 && $amount > 0 && $walletNumber != '')
		{	
			$note = self::getPaymentNote($login, $structureNumber, $matrixNumber, '', $amount, true, $matrixID, $type);
			//$paymentResult = self::makePayment($amount, $walletNumber, $note);
			$paymentResult = [true, 'test', '721999713', 'USD'];
			print_r($paymentResult);
			if(isset($paymentResult[0]) && isset($paymentResult[1]) && isset($paymentResult[3]))
			{	
				if(($paymentResult[0]) && $paymentResult[2] > 0 && $paymentResult[3] != '')
				{	$dbModel = new DbBase();
					$autoPayment = ($auto) ? 1 : 0;
					$procedureInData = [$id, $partnerID, $structureNumber, $matrixNumber, $matrixID, $type, $amount, $paymentResult[2], $paymentResult[3], 'payeer', $autoPayment, '@p1'];
					$procedureOutData = ['@p1'=>'VAR_OUT_RESULT'];
					echo '<pre>';
					print_r($procedureInData);
					echo '</pre>';
					
					$procedureResult = $dbModel->callProcedure('auto_payment', $procedureInData, $procedureOutData);
					$result = $procedureResult['result'];
				}
			}
		}
	
		return $result;
	}
	
	public static function checkUser($accountNumber, $apiID, $apiPass, $walletNumber, $amount, $currency, $payeerClass)
	{
		$result = false;
		$params['account'] = $accountNumber;
		$params['apiId'] = $apiID;
		$params['apiPass'] = $apiPass;
		$params['action'] = 'checkUser';
		$params['user'] = $walletNumber;
		
		$result = $payeerClass->checkUser($params);
		
		return $result;
	}
	
	public static function transfer($accountNumber, $apiID, $apiPass, $walletNumber, $currency, $amount, $note, $payeerClass)
	{
		$result = false;
		
		$params['account'] = $accountNumber;
		$params['apiId'] = $apiID;
		$params['apiPass'] = $apiPass;
		$params['action'] = 'transfer';
		$params['sum'] = $amount;
		$params['curIn'] = $currency;
		$params['curOut'] = $currency;
		$params['sumOut'] = $amount;
		$params['to'] = $walletNumber;
		$params['comment'] = $note;
		
		$result = $payeerClass->transfer($params);
		
		return $result;
	}
}
