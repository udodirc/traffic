<?php
namespace common\components\advacash\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\components\advacash\Api;

class Advacash extends Model
{
	public function makePayment($amount, $walletNumber, $note)
    {
		$result = false;
		$msg = Yii::t('messages', 'Ошибка!');
		$transactionID = '';
		
		if(isset(Yii::$app->params['advacash_api']) && isset(Yii::$app->params['advacash_api']['account_email']) && isset(Yii::$app->params['advacash_api']['name']) && isset(Yii::$app->params['advacash_api']['password']))
		{
			$api = new Api();
			$api->accountEmail = Yii::$app->params['advacash_api']['account_email'];
			$api->name = Yii::$app->params['advacash_api']['name'];
			$api->password = Yii::$app->params['advacash_api']['password'];
			$systemWallet = Yii::$app->params['advacash_api']['wallet_number'];
			
			$balances = $api->getBalances();
			$balances = ArrayHelper::index($balances, 'id');
			
			if(isset($balances[$systemWallet]))
			{
				$balanceAmount = $balances[$systemWallet]['amount'];
				
				if($balanceAmount >= $amount)
				{
					$walletNumber = trim($walletNumber);
					$response = $api->validateSendMoney($amount, 'USD', '', $walletNumber, $note, false);
					
					if(empty((array)$response))
					{
						$msg = Yii::t('messages', 'Оплата не пройдена!');
						$transactionID = $api->sendMoney($amount, 'USD', '', $walletNumber, $note, false);
						
						if($transactionID != '')
						{
							$result = true;
							$msg = Yii::t('messages', 'Оплата совершенна успешна!');
						}
					}
					else
					{
						$msg = Yii::t('messages', 'Валидация оплаты не пройдена!');
					}
				}
				else
				{
					$msg = Yii::t('messages', 'На балансе недостаточно средств!');
				}
			}
		}
		
		return [$result, $msg, $transactionID];
	}
	
	public function getHistory()
    {
		$result = [];
		
		if(isset(Yii::$app->params['advacash_api']) && isset(Yii::$app->params['advacash_api']['account_email']) && isset(Yii::$app->params['advacash_api']['name']) && isset(Yii::$app->params['advacash_api']['password']))
		{
			$api = new Api();
			$api->accountEmail = Yii::$app->params['advacash_api']['account_email'];
			$api->name = Yii::$app->params['advacash_api']['name'];
			$api->password = Yii::$app->params['advacash_api']['password'];
			$systemWallet = Yii::$app->params['advacash_api']['wallet_number'];
			
			//$result = $balances = $api->getBalances();
			//$result = $api->history($api->name, '2018-01-01T00:00:00', '2018-03-01T00:00:00', 'ALL', 'COMPLETED', '2018-01-01T00:00:00', '2018-03-01T00:00:00');
			$result = $api->history(0, 1000000, 'DESC', '2017-01-01T00:00:00', '2019-04-01T00:00:00', 'INNER_TRANSACTION', 'COMPLETED');
		}
		
		return $result;
	}
	
	public static function getDataFromNotation($note, $separator, $index)
    {
		return trim(substr(strstr($note, $separator), $index));
	}
	
	public static function getPayMatrixFromNotation($payMatrix)
    {
		$result = 0;
		
		$payMatrix = self::getDataFromNotation($payMatrix, '-', 1);
		
		if($payMatrix != '')
		{
			$payMatrix = explode(" ", $payMatrix);
			
			if(!empty($payMatrix))
			{ 
				$result = (isset($payMatrix[0])) ? $payMatrix[0] : 0;
			}
		}
		
		return $result;
	}
}
?>
