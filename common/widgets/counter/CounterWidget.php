<?php
namespace common\widgets\counter;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\widgets\counter\assets\CounterAsset;

class CounterWidget extends Widget
{
	public $options;
	public $template = '
	<div id="count_down"></div>';
	
	public function run()
	{
		if(isset(Yii::$app->params['counter']['is_enable']) && (Yii::$app->params['counter']['is_enable']) && isset(Yii::$app->params['counter']['content']) && isset(Yii::$app->params['counter']['date']))
		{
			if(!Yii::$app->params['counter']['content'])
			{
				echo $this->template;
			}
		
			$date = Yii::$app->params['counter']['date'];
			$this->registerClientScript($date);
		}
	}
	
	/**
     * Registers required script for the plugin
     */
    public function registerClientScript($date)
    {
        $view = $this->getView();
		CounterAsset::register($view);
		
		$year = (isset($date['year'])) ? $date['year'] : 0;
		$month = (isset($date['month'])) ? $date['month'] : 0;
		$day = (isset($date['day'])) ? $date['day'] : 0;
		/*$now = new DateTime();
		$inlineScript = "$(function () {
			var austDay = new Date(".($registerDate * 1000).");
			var serverTime = '".$now->format("M j, Y H:i:s O")."';
			$('#count_down').countdown({until: austDay, serverSync: serverTime});
		});";*/
		$inlineScript = "$(function () {
			$('#count_down').countdown({until: new Date(".$year.", ".$month." - 1, ".$day.")});
		});";
		$view->registerJs($inlineScript,  View::POS_END);
    }
}
