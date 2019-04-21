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
class AppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/frontend';
    public $sourcePath = '@app/themes/frontend';
    public $css = [
		'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/nprogress.css',
        'css/jquery.mCustomScrollbar.min.css',
    ];
    public $js = [
		'js/bootstrap.min.js',
		'js/fastclick.js',
		'js/nprogress.js',
		'js/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
		'js/scripts.js',
		'js/menu.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
