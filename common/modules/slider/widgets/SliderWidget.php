<?php
namespace common\modules\slider\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\modules\uploads\models\Files;
use common\modules\slider\assets\SliderAsset;

class SliderWidget extends Widget
{
	public $item_list;
	public $category;
	public $options;
	public $slider_number;
	public $template = '
	<div class="slider_image">
		<h1 class="slide-title heading-style-1 visible-first">
			<span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first">{title}</span> 
		</h1>
		<p>{content}</p>
		{image}
	</div>';
	
	public function run()
	{
		if(isset(Yii::$app->params['slider']['is_enable']) && (Yii::$app->params['slider']['is_enable']) && !is_null($this->item_list) && !empty($this->item_list))
		{
			echo $this->renderItems();
			$this->registerClientScript();
		}
	}
	
	protected function renderItems()
	{
		$lines = [];
		$alias = isset(Yii::$app->params['upload_dir'][$this->category]['alias']) ? Yii::$app->params['upload_dir'][$this->category]['alias'] : 'frontend_uploads';
		$dir = ((isset(Yii::$app->params['upload_dir'][$this->category]['dir']) && Yii::$app->params['upload_dir'][$this->category]['dir'] != '') && ((isset(Yii::$app->params['upload_dir'][$this->category]['uploads']) && Yii::$app->params['upload_dir'][$this->category]['uploads'] != ''))) ? Yii::$app->params['upload_dir'][$this->category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$this->category]['uploads'] : '';
		$lines[] = '
			<div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 1279px; height: 389px; ">
			<div u="slides" style="position: absolute; left: 0px; top: 0px; width: 1279px; height: 389px; overflow: hidden;">';
			
			foreach ($this->item_list as $i => $item) 
			{	
				$files = Files::getFiles($this->category, false, $item['id']);
					
				if(!empty($files))
				{
					$file = $files[0];
						
					if(is_file($file))
					{	
						$file = explode('/', $file);
						$file = end($file);
						$lines[] = $this->renderItem($item, $file, $dir);
					}
				}
			}
			
		$lines[] = '
			</div>
			<div data-u="arrowleft" class="jssora051" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
				<svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
					<polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
				</svg>
			</div>
			<div data-u="arrowright" class="jssora051" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
				<svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
					<polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
				</svg>
			</div>
		</div>';
		
		return implode("\n", $lines);
	}
	
	protected function renderItem($item, $file, $dir)
    {
		return strtr($this->template, [
			'{image}' => Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.\Yii::getAlias('@admin_uploads').DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$item['id'].DIRECTORY_SEPARATOR.$file, ['alt'=>'logo', 'u'=>'image']),
			'{title}' => $item['title'],
			'{content}' => $item['content'],
		]);
    }
    
    /**
     * Registers required script for the plugin
     */
    public function registerClientScript()
    {
        $view = $this->getView();
		SliderAsset::register($view);
		$options = '';
		$js = [];
		
		if(!empty($this->options)) 
		{
			$options = 'var options = {';
			
            foreach($this->options as $name => $value) 
            {
				if(is_array($value))
				{
					$js[] = $name.': {';
					
					foreach($value as $name2 => $value2) 
					{	
						$js[] = $name2.':'.$value2.',';
					}
					
					$js[] = '},';
				}
				else
				{
					if($name != '' && $value != '')
					{	
						$js[] = $name.':'.$value.',';
					}
				}
            }
            
            $options.= implode("\n", $js);
            $options.= '};';
        }
        
		$inlineScript = 'jssor_slider'.$this->slider_number.'_init = function () {
			'.$options.'

		   var jssor_slider1 = new $JssorSlider$("slider'.$this->slider_number.'_container", options);
		   
		   var MAX_WIDTH = 3000;

            function ScaleSlider() {
				
                var containerElement = jssor_slider1.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;
				
                if (containerWidth) {
					var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);
					
                    jssor_slider1.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();

            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
		};';
		$view->registerJs($inlineScript,  View::POS_END);
		$inlineScript = 'jssor_slider'.$this->slider_number.'_init();';
		$view->registerJs($inlineScript,  View::POS_END);
    }
}
