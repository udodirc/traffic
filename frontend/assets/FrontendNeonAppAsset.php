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
class FrontendNeonAppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/frontend_neon';
    public $sourcePath = '@app/themes/frontend_neon';
    public $css = [
		'assets/css/bootstrap.css',
		'assets/css/font-icons/entypo/css/entypo.css',
		'assets/css/neon.css',
    ];
    public $js = [
		'assets/js/ie8-responsive-file-warning.js',
		'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
		'https://oss.maxcdn.com/respond/1.4.2/respond.min.js',
	];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
