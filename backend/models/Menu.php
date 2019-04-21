<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $url
 */
class Menu extends \yii\db\ActiveRecord
{
	public $menu;
	public $menu_name;
	public $content_name;
	public $controller;
	public $controller_data;
	public $status;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            ['name', 'checkUniqueMenu'],
            [['name', 'url'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
            [['name', 'url'], 'string', 'min' => 2, 'message' => Yii::t('form', 'В этом поле минимально допустимое количество символов 2!')],
            ['url', 'checkUniqueUrl'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'parent_id' => Yii::t('form', 'Parent ID'),
            'name' => Yii::t('form', 'Название меню'),
            'url' => Yii::t('form', 'Url'),
            'menu' => Yii::t('form', 'Меню'),
            'controller' => Yii::t('form', 'Контроллер'),
        ];
    }
    
    public function checkUniqueMenu()
    {	
		$condition = ($this->id > 0) ? ' AND id != :id' : '';
		$params = ($this->id > 0) ? array(':name' => $this->name, ':id' => $this->id) : array(':name' => $this->name);
		
		$data = self::find()
		->select('id')
		->from('menu')
		->where('name=:name'.$condition, $params)
		->one();
		
		if($data !== null)
		{
			$this->addError('name', Yii::t('form', 'Такое меню уже существует!'));
		}
    }
    
    public function checkUniqueUrl()
    {	
		$condition = ($this->id > 0) ? ' AND id != :id' : '';
		$params = ($this->id > 0) ? array(':url' => $this->url, ':id' => $this->id) : array(':url' => $this->url);
		
		$data = self::find()
		->select('id')
		->from('menu')
		->where('url=:url'.$condition, $params)
		->one();
		
		if($data !== null)
		{
			$this->addError('url', Yii::t('form', 'Такой url уже существует!'));
		}
	}
	
	public function getMenuListByIDIndex($admin = false, $parent = false)
    {
		$result = array();
		$query = (new \yii\db\Query())
		->select(['id', 'name'])
		->from(($admin) ? 'admin_menu' : 'menu')
		->where(($parent) ? 'parent_id > 0' : 'parent_id = 0')
		->orderBy('parent_id');
		
		foreach($query->each() as $i => $menu)
		{
			$result[$menu['id']] = $menu['name'];
		}
		
		return $result;
	}
	
	public function getMenuDataByID($id)
    {
		return self::find()
		->select('menu_1.name AS menu_name, content.title as content_name, menu_2.id, menu_2.controller_id, menu_2.name AS name, menu_2.url, menu_2.head')
		->from('menu menu_1')
		->rightJoin('menu menu_2', 'menu_1.id = menu_2.parent_id')
		->leftJoin('content', 'content.id = menu_2.content_id')
		->where('menu_2.id=:id', [':id' => $id])
		->one();
	}
	
	public function getMenuDataByName($name, $admin = false)
    {
		$data = self::find()
		->select('id')
		->from(($admin) ? 'admin_menu' : 'menu')
		->where('name=:name', [':name' => $name])
		->one();
		
		return ($data !== null) ? $data->id : 0;
	}
	
	public function getMenuNameByID($id)
    {
		$data = self::find()
		->select('name')
		->from('menu')
		->where('id=:id', [':id' => $id])
		->one();
		
		return ($data !== null) ? $data->name : '';
	}
	
	public function deleteMenu($id)
    {
		if(($model = Menu::findOne($id)) !== null) 
		{
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
			
			try 
			{
				$menu_model = $connection->createCommand()
				->delete('menu', [
					'parent_id' => $model->id
				])
				->execute();
	
				$connection->createCommand()
				->delete('menu', [
					'id' => $model->id
				])
				->execute();
	
				$transaction->commit();
			} 
			catch(Exception $e) 
			{
				$transaction->rollback();
			}
        }
	}
	
	public static function getMenuTyperByControllerID($id)
    {
		$controllersArr = Yii::$app->params['controllers'];
		
		return ($controllersArr[$id]) ? $controllersArr[$id]['name'] : '';
	}
}
