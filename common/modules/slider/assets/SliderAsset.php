<?php
namespace common\modules\slider\assets;
use yii\web\AssetBundle;

class SliderAsset extends AssetBundle
{
	public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@common/modules/slider/assets';
    public $sourcePath = '@common/modules/slider/assets';
    public $css = [
		'css/style.css'
    ];
    public $js = [
		'js/jssor.slider.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
