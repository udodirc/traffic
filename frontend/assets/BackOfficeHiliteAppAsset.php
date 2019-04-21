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
class BackOfficeHiliteAppAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
    
    public $baseUrl = '@app/themes/backoffice-hilite';
    public $sourcePath = '@app/themes/backoffice-hilite';
    public $css = [
		'assets/vendors/mdi/css/materialdesignicons.min.css',
		'assets/vendors/css/vendor.bundle.base.css',
		'assets/vendors/flag-icon-css/css/flag-icon.min.css',
		'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
		'assets/css/vertical-layout-light/style.css',
		'assets/css/vertical-layout-light/custom.css',
    ];
    public $js = [
		'assets/vendors/js/vendor.bundle.base.js',
		'assets/js/off-canvas.js',
		'assets/js/hoverable-collapse.js',
		'assets/js/template.js',
		'assets/js/settings.js',
		'assets/js/todolist.js',
		'assets/vendors/datatables.net/jquery.dataTables.js',
		'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js',
		'assets/js/data-table.js',
	];
    public $depends = [
        /*'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',*/
    ];
}
