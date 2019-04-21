<?php
namespace common\widgets\running_geo_data;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\widgets\running_geo_data\assets\GeoAsset;

class RunningGeoDataWidget extends Widget
{
	public $item_list;
	public $options;
	public $template = '
	<li>
		<div class="geo_data_wrap">
			<div class="geo_image_wrap">
				{image}
			</div>
			<div class="geo_info_wrap">
				<span class="geo_info_login">{login}</span><br/>
				<span class="geo_info_date">{register_date}</span>
			</div>
		</div>
	</li>';
	
	public function run()
	{
		echo $this->renderItems();
		
		$this->registerClientScript();
	}
	
	protected function renderItems()
	{
		$lines = [];
		
		if(!is_null($this->item_list) && !empty($this->item_list))
		{
			$lines[] = '<ul class="marquee-content-items">';
			
			foreach ($this->item_list as $i => $item) 
			{
				if(!empty($item['geo']))
				{
					$lines[] = $this->renderItem($item);
				}
			}
			
			$lines[] = '</ul>';
		}
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($item)
    {
		$iso = (isset($item['geo'][0])) ? $item['geo'][0] : '';
		$country = (isset($item['geo'][1])) ? $item['geo'][1] : '';
		
		return strtr($this->template, [
			'{image}' => ($iso != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($iso).'.png'), ['alt'=>$country, 'title'=>$country]) : $country,
			'{login}' => $item['login'],
			'{register_date}' => date("Y-m-d", $item['created_at'])
		]);
    }
	
	/**
     * Registers required script for the plugin
     */
    public function registerClientScript()
    {
        $view = $this->getView();
		GeoAsset::register($view);
		$options = '';
		$js = [];
		
		if(!empty($this->options)) 
		{
            foreach ($this->options as $name => $value) 
            {
				if($name != '' && $value != '')
				{
					$js[] = $name.':'.$value.',';
				}
            }
            
            $options = implode("\n", $js);
        }
        
		$inlineScript = "$(function () {
			$('.simple-marquee-container').SimpleMarquee({
				".$options."
			});
		});";
		$view->registerJs($inlineScript,  View::POS_END);
    }
}
