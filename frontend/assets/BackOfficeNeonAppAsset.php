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
class BackOfficeNeonAppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/backoffice_neon';
    public $sourcePath = '@app/themes/backoffice_neon';
    public $css = [
		'assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css',
		'assets/css/font-icons/entypo/css/entypo.css',
		'//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic',
		'assets/css/bootstrap.css',
		'assets/css/neon-core.css',
		'assets/css/neon-theme.css',
		'assets/css/neon-forms.css',
		'assets/css/custom.css',
    ];
    public $js = [
		'assets/js/ie8-responsive-file-warning.js',
		'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
		'https://oss.maxcdn.com/respond/1.4.2/respond.min.js',
		'assets/js/resizeable.js',
		'assets/js/core.js',
		'assets/js/advertisement.js',
	];
    public $depends = [
        /*'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',*/
    ];
}
