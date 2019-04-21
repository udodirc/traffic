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
        'css/components.css',
        'css/custom.css',
        'css/style.css',
        'css/pages/new_dashboard.css',
    ];
    public $js = [
		'https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js',
		//'js/bootstrap.min.js',
		'js/components.js',
		'js/custom.js',
		'js/pages/jquery.ui.min.js',
		'js/advertisement.js',
		'js/core.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
