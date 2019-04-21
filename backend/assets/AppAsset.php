<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

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
    
    public $baseUrl = '@app/themes/admin';
    public $sourcePath = '@app/themes/admin';
    public $css = [
        'css/_layout/1140.css',
        'css/_layout/styles.css',
        'css/_themes/default.css',
        'css/site.css',
        'css/fileuploader/fileuploader.css',
        'css/style.css',
        'css/_layout/scripts/css3-mediaqueries.js',
    ];
    public $js = [
		'js/core.js',
		//'js/uploads.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
