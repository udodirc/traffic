<?php
namespace common\config;

use Yii;
use yii\base\BootstrapInterface;
use app\models\AdminMenu;

/*
/* The base class that you use to retrieve the settings from the database
*/

class admin_top_menu implements BootstrapInterface {

    private $db;

    public function __construct() 
    {
        $this->db = Yii::$app->db;
    }

    /**
    * Bootstrap method to be called during application bootstrap stage.
    * Loads all the settings into the Yii::$app->params array
    * @param Application $app the application currently running
    */

    public function bootstrap($app) 
    {
		$AdminMenuModel = new AdminMenu;
		Yii::$app->params['admin_top_menu'] = $AdminMenuModel->getAdminTopMenuList();
    }
}
?>
