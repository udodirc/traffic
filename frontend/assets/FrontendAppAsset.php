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
class FrontendAppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/frontend';
    public $sourcePath = '@app/themes/frontend';
    public $css = [
		'css/layout.css',
		'css/template.css',
		'css/font-awesome.css',
		'css/fl-puppets.css',
		'css/gallery.css',
		'css/komento.css',
		'css/navbar.css',
		'css/style.css',
    ];
    public $js = [
		//'js/jquery.js',
		//'js/jquery-migrate-1.2.1.min.js',
		'js/jquery.BlackAndWhite.min.js',
		//'js/menu.js',
		'js/jquery.rd-navbar.js',
		'js/scripts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
