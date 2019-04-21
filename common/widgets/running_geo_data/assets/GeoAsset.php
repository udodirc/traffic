<?php
namespace common\widgets\running_geo_data\assets;
use yii\web\AssetBundle;

class GeoAsset extends AssetBundle
{
	public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@common/widgets/running_geo_data/assets';
    public $sourcePath = '@common/widgets/running_geo_data/assets';
    public $css = [
		'css/marquee.css'
    ];
    public $js = [
		'js/marquee.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
