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
		'assets/css/template.css',
		'assets/css/font-awesome.css',
		'assets/css/color_schemes/color_scheme_1.css',
		'assets/css/jquery.fancybox.css',
		'assets/css/jquery.fancybox-buttons.css',
		'assets/css/jquery.fancybox-thumbs.css',
		'assets/css/fancybox.css',
		'assets/css/portfolio.css',
		'assets/css/swiper.css',
		'assets/css/animate.css',
		'assets/css/animate.css',
		'assets/css/menu/navbar.css',
    ];
    public $js = [
		'assets/js/jquery.fancybox.pack.js',
		'assets/js/jquery.fancybox-thumbs.js',
		'assets/js/jquery.pep.js',
		//'assets/js/menu/menu.js',
		'assets/js/menu/jquery.rd-navbar.js',
		'assets/js/jquery-migrate-1.2.1.min.js',
		'assets/js/scripts.js',
	];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
