<?php
namespace common\config;

use Yii;
use yii\base\BootstrapInterface;

/*
/* The base class that you use to retrieve the settings from the database
*/

class pagination implements BootstrapInterface {

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
		// Get settings from database
		$query = (new \yii\db\Query())
		->select('admin_menu.url, pagination.value')
		->from('pagination')
		->leftJoin('admin_menu', 'admin_menu.id = pagination.menu_id');
		
		// Now let's load the settings into the global params array
		foreach($query->each() as $i => $val)
		{
			Yii::$app->params['pagination'][$val['url']] = $val['value'];
		}
    }
}
?>
