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
class FrontendLandXAppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/frontend-landx';
    public $sourcePath = '@app/themes/frontend-landx';
    public $css = [
		'assets/css/bootstrap.css',
		'assets/assets/ionicons/css/ionicons.css',
		'assets/assets/elegant-icons/style.css',
		'assets/css/owl.theme.css',
		'assets/css/owl.carousel.css',
		'assets/css/nivo-lightbox.css',
		'assets/css/nivo_themes/default/default.css',
		'assets/css/colors/blue.css',
		'assets/css/styles.css',
		'assets/css/responsive.css',
		'assets/css/custom.css',
    ];
    public $js = [
		'assets/js/html5shiv.js',
		'assets/js/respond.min.js',
		'assets/js/retina-1.1.0.min.js',
		'assets/js/smoothscroll.js',
		'assets/js/jquery.scrollTo.min.js',
		'assets/js/jquery.localScroll.min.js',
		'assets/js/owl.carousel.min.js',
		'assets/js/nivo-lightbox.min.js',
		'assets/js/simple-expand.min.js',
		'assets/js/jquery.nav.js',
		'assets/js/jquery.fitvids.js',
		'assets/js/jquery.ajaxchimp.min.js',
		'assets/js/custom.js',
		'assets/js/script.js',
	];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
