<?php
namespace common\modules\uploads\assets;
use yii\web\AssetBundle;

class UploadsAsset extends AssetBundle
{
	public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@uploads-assets';
    public $sourcePath = '@uploads-assets';
    public $css = [
	];
    public $js = [
		'js/uploads.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
