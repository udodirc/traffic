<?php
namespace common\widgets\counter\assets;
use yii\web\AssetBundle;

class CounterAsset extends AssetBundle
{
	public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@common/widgets/counter/assets';
    public $sourcePath = '@common/widgets/counter/assets';
    public $css = [
		'css/jquery.countdown.css'
    ];
    public $js = [
		'js/jquery.plugin.min.js',
		'js/jquery.countdown.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
