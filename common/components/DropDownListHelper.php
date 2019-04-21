<?php

namespace common\components;

use yii;
use app\models\UserGroups;
use app\models\AdminMenu;
use common\models\Menu;
use common\models\Service;
use app\modules\services\models\ServicesCategories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DropDownListHelper
{
	public static function getDropDownList($form, $model, $dataModel, $menuName, $modelDir, $options = array(), $where = array())
    {
		$result = '';
		$className = $modelDir.'\models\\'.$dataModel;
		$dataModel = new $className();
		$dataList = (!empty($where)) ? $dataList = ArrayHelper::map($dataModel::find()->where($where[0], $where[1])->asArray()->all(), 'id', 'name') : ArrayHelper::map($dataModel::find()->asArray()->all(), 'id', 'name');
		
		$result = $form->field($model, $menuName)
			->dropDownList(
			$dataList,
			$options // options
		);
		
		return $result;
	}
	
	public static function getControlersDropDownList($form, $model, $menuName, $params, $index, $options = array())
    {
		$result = '';
		$dataList = Service::getControllersList($params, $index);
		
		$result = $form->field($model, $menuName)
			->dropDownList(
			$dataList,
			$options // options
		);
		
		return $result;
	}
	
	public static function getHtmlDropDownList($dataModel, $modelDir, $selectArr = array(), $where = array(), $menuName, $options = array())
    {
		$result = '';
		$className = $modelDir.'\models\\'.$dataModel;
		$dataModel = new $className();
		$dataList = ArrayHelper::map($dataModel::find()->select($selectArr[0].', '.$selectArr[1])->where($where[0], $where[1])->asArray()->all(), $selectArr[0], $selectArr[1]);
		
		if(!empty($dataList))
		{
			$result = Html::dropDownList($menuName, null, $dataList, $options);
		}
		
		return $result;
	}
}
?>
