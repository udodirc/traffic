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
		'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/nprogress.css',
        'css/jquery.mCustomScrollbar.min.css',
        'css/custom.min.css',
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/nprogress.css',
        'css/jquery.mCustomScrollbar.min.css',
        'css/dataTables.bootstrap.min.css',
        'css/buttons.bootstrap.min.css',
        'css/fixedHeader.bootstrap.min.css',
        'css/responsive.bootstrap.min.css',
        'css/scroller.bootstrap.min.css',
        'css/style.css',
    ];
    public $js = [
		'js/bootstrap.min.js',
		'js/fastclick.js',
		'js/nprogress.js',
		'js/jquery.mCustomScrollbar.concat.min.js',
		'js/custom.min.js',
		'js/core.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
