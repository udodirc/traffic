<?php
namespace common\modules\seo\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Settings;

class CounterWidget extends Widget
{
	public $counter_name;
	
	public function run()
	{
		echo $this->renderItem();
	}
	
	protected function renderItem()
	{
		$counter = '';
		
		if($this->counter_name != '')
		{
			$counter = (Settings::find()->select('value')->where(['name'=>$this->counter_name]) !== null) ? Settings::find()->select('value')->where(['name'=>$this->counter_name])->one() : '';
			$counter = Html::decode(($counter != '') ? $counter->value : '');
		}
		
		return $counter;
	}
}
