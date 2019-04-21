<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BackOfficeAppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/backoffice';
    public $sourcePath = '@app/themes/backoffice';
    public $css = [
		'vendor/pace/pace-theme-minimal.css',
		'vendor/bootstrap/css/bootstrap.css',
		'vendor/font-awesome/css/font-awesome.css',
		'vendor/animate.css/animate.css',
		'stylesheets/css/style.css',
    ];
    public $js = [
		'vendor/pace/pace.min.js',
		'vendor/jquery/jquery-1.12.3.min.js',
		'vendor/bootstrap/js/bootstrap.min.js',
		'vendor/nano-scroller/nano-scroller.js',
		'javascripts/template-script.min.js',
		'javascripts/template-init.min.js',
		'javascripts/core.js',
		'javascripts/advertisement.js',
	];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
