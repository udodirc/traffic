<?php
namespace common\components;

use yii\web\UrlManager;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CustomUrlManager extends UrlManager
{
	public function addRules($rules, $append = true)
    {
		$connection = \Yii::$app->db;
		$sql = "SELECT `url` FROM `menu` WHERE `status` > 0";
		$urlList = $connection->createCommand($sql)->queryAll();
		
		if(!empty($urlList))
		{
			$rules = array_fill_keys(array_keys(array_flip(ArrayHelper::getColumn($urlList, 'url'))), 'menu/index');
		}
		
        if (!$this->enablePrettyUrl) {
            return;
        }
        $rules = $this->buildRules($rules);
        if ($append) {
            $this->rules = array_merge($this->rules, $rules);
        } else {
            $this->rules = array_merge($rules, $this->rules);
        }
    }
}
