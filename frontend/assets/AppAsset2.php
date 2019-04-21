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
		'css/bxslider.css',
        'css/fancybox.css',
        'css/select2.min.css',
        'css/styles.css',
    ];
    public $js = [
		'js/jquery-migrate.js',
		'js/selectbox.js',
		'js/bxslider.js',
		'js/fancybox.js',
		'js/dropzone.js',
		'js/scripts.js',
		'js/select2.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
