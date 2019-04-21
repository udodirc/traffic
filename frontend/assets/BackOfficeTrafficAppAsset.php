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
		//'assets/css/bootstrap.min.css',
		'assets/font-awesome/css/font-awesome.css',
		'assets/css/animate.css',
		'assets/css/style.css',
		'assets/css/custom.css',
		'assets/css/fileuploader/fileuploader.css',
    ];
    public $js = [
		//'assets/js/jquery-3.1.1.min.js',
		'assets/js/bootstrap.min.js',
		'assets/js/plugins/metisMenu/jquery.metisMenu.js',
		'assets/js/plugins/slimscroll/jquery.slimscroll.min.js',
		'assets/js/inspinia.js',
		'assets/js/plugins/pace/pace.min.js',
		'assets/js/core.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
