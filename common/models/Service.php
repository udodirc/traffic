<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\Curl;
use backend\models\Settings;
use common\modules\structure\models\Matrix;

/**
 * Service class.
 * Service class is the class for keeping of common methods and attributes 
 * for all model and controllers in project
 */
class Service 
{
	public static function getControllerNameByID($id, $index = 'name')
    {
		$controllersArr = Yii::$app->params['controllers'];
		
		return (isset($controllersArr[$id])) ? $controllersArr[$id][$index] : '';
	}
	
	public static function getControllerIDByName($name)
    {
		$controllersArr = Yii::$app->params['controllers'];
		$controllersArr = array_flip(array_combine(array_keys($controllersArr), array_column($controllersArr, 'name')));
		
		return (isset($controllersArr[$name])) ? $controllersArr[$name] : 0;
	}
	
	public static function getControllersList($params = 'controllers', $index = 'name')
    {
		$controllersArr = ($params !== 'controllers') ? array_intersect_key(Yii::$app->params['controllers'], array_flip(Yii::$app->params[$params])) : Yii::$app->params[$params];
		$result = array_combine(array_keys($controllersArr), array_column($controllersArr, $index));
		
		return $result;
	}
	
	public static function getPageSize()
    {
		return (isset(Yii::$app->params['pagination'][Yii::$app->controller->id])) ? Yii::$app->params['pagination'][Yii::$app->controller->id] : Yii::$app->params['pagination']['default'];
	}
	
	public static function getLastParamsInUrl()
    {
		return $basename = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
	}
	
	public static function getControllerID($id)
    {
		$controllersArr = Yii::$app->params['controllers'];
		$controllerArr = array_filter($controllersArr, function($ar) {
			return ($ar['controller'] == ucfirst(self::dashesToCamelCase(Yii::$app->controller->id)));
		});
		reset($controllerArr);
		$result = (key($controllerArr) !== NULL) ? key($controllerArr) : 0;

		return $result;
	}
	
	public static function dashesToCamelCase($string, $capitalizeFirstCharacter = false) 
	{

		$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

		if (!$capitalizeFirstCharacter) 
		{
			$str[0] = strtolower($str[0]);
		}

		return $str;
	}
	
	public static function getBitcoinExchangeRates()
	{
		$result = [];
		
		$url = (isset(Yii::$app->params['bitcoin_exchange_rates_url'])) ? Yii::$app->params['bitcoin_exchange_rates_url'] : '';
		
		if($url != '')
		{
			$currency = (isset(Yii::$app->params['bitcoin_exchange_currency'])) ? Yii::$app->params['bitcoin_exchange_currency'] : 'USD';
			$curl = new Curl();
			$exchangeRates = $curl->get($url);
			
			if($exchangeRates != '')
			{
				$result = json_decode($exchangeRates, true);
				$result = (!empty($result) && isset($result[$currency]['15m'])) ? $result[$currency]['15m'] : [];
			}
		}
		
		return $result;
	}
	
	public static function getActivationAmount($count)
	{
		$result = 0;
		
		if($count > 0)
		{
			if(isset(Yii::$app->params['bitcoin_activation_amount']))
			{
				$activationAmount = Yii::$app->params['bitcoin_activation_amount'];
				$exchangeRates = Service::getBitcoinExchangeRates();
				
				if($exchangeRates > 0)
				{
					$result = $activationAmount * $count * $exchangeRates;
				}
			}
			else
			{
				$activationAmount = (isset(Yii::$app->params['activation_amount'])) ? Yii::$app->params['activation_amount'] : 0;
				
				if($activationAmount > 0)
				{
					$result = $activationAmount * $count;
				}
			}
		}
		
		return $result;
	}
	
	public static function getMatricesList($number)
    {
		$result = [];
		
		if($number > 0)
		{
			for($i=1; $i<=$number; $i++)
			{
				$result[$i] = Yii::t('form', 'Матрица').' '.$i;
			}
		}
		
		return $result;
	}
	
	public static function generateRandomID($min = 0, $max = 15)
    {
		return rand($min, $max);
	}
	
	public static function getSessionID()
    {
		if(!\Yii::$app->session->has('session_id'))
		{	
			\Yii::$app->session->set('session_id', session_id());
		}

		return (\Yii::$app->session->has('session_id')) ? \Yii::$app->session->get('session_id') : '';
	}
	
	public static function checkLoginWhiteList($login)
    {
		$result = false;
		
		if(isset(Yii::$app->params['login_white_list']))
		{
			$loginList = array_flip(Yii::$app->params['login_white_list']);
			
			if(isset($loginList[$login]))
			{
				$result = true;
			}
		}
		
		return $result;
	}
	
	public static function isActionAllowed($action)
    {
		$data = Settings::find(['value'])->where(['name'=>$action])->one();
		
		return ($data != null) ? (($data->value > 0) ? true : false) : false;
	}
	
	public static function getListViewType($structure, $number)
    {
		$result = (isset(\Yii::$app->params['list_view_count'])) ? \Yii::$app->params['list_view_count'] : 0;
		$listViewCount = (isset(\Yii::$app->params['list_view_count'])) ? \Yii::$app->params['list_view_count'] : 0;
		
		if($result > 0 && $listViewCount > 0)
		{
			$matricesSettings = Matrix::getMatricesSettings($structure, $number);
			
			if($matricesSettings['levels'] > 0)
			{
				$result = ($matricesSettings['levels'] > $listViewCount) ? 1 : 0; 
			}
		}
		
		return $result;
	}
}
?>
