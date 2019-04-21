<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use app\models\AdminMenu;
use common\models\Service;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "permissions".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $controller
 * @property blob    $permissions
 * @property integer $update_perm
 * @property integer $delete_perm
 */
class Permissions extends \yii\db\ActiveRecord
{
	public $view_perm;
	public $create_perm;
	public $update_perm;
	public $delete_perm;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permissions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'controller_id'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            ['controller_id', 'unique', 'targetAttribute' => ['group_id', 'controller_id'], 'message' => Yii::t('form', 'Уже есть такие права!')],
            [['group_id', 'controller_id', 'view_perm', 'create_perm', 'update_perm', 'delete_perm'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'group_id' => Yii::t('form', 'Группа'),
            'controller_id' => Yii::t('form', 'Контроллер'),
            'view_perm' => Yii::t('form', 'Просмотр'),
            'create_perm' => Yii::t('form', 'Создание'),
            'update_perm' => Yii::t('form', 'Редактирование'),
            'delete_perm' => Yii::t('form', 'Удаление'),
        ];
    }
    
    public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) 
		{
			$this->permissions = serialize(['view_perm'=>$this->view_perm, 'create_perm'=>$this->create_perm, 'update_perm'=>$this->update_perm, 'delete_perm'=>$this->delete_perm]);
			
			return true;
		}
		
		return false;
	}
	
	public static function getPermissionsList()
    {
		$result = array();
		$data = self::find()->asArray()->all();
		
		foreach($data as $permission)
		{
			$result[$permission['group_id']][$permission['controller_id']] = unserialize($permission['permissions']);
		}
		
		return $result;
	}
	
	public static function setPermissionsData($model, $permissions)
    {
		$model->view_perm = ($permissions['view_perm'] > 0) ? true : false;
		$model->create_perm = ($permissions['create_perm'] > 0) ? true : false;
		$model->update_perm = ($permissions['update_perm'] > 0) ? true : false;
		$model->delete_perm = ($permissions['delete_perm'] > 0) ? true : false;
	}
	
	public static function checkPermissionsByUrl()
    {
		$pages = array_flip(Yii::$app->params['permitted_pages']);
		$url = ((substr(Url::to(""), 0, strlen(Url::base().'/')) == Url::base().'/')) ? substr(Url::to(""), strlen(Url::base().'/')) : '';
		
		if(strpos($url, '/'))
		{
			$url = explode('/', $url);
			$url = $url[0].'/'.$url[1];
		}

		return (isset($pages[$url])) ? true : false;
	}
	
	public function getGroups()
    {
        return $this->hasOne(UserGroups::className(), ['id' => 'group_id']);
    }
	
	public function getPermissionsDataByControllerID($userID, $controllerID)
    {
		return self::find()
		->select('permissions.permissions')
		->from('permissions')
		->leftJoin('user_groups', 'user_groups.id = permissions.group_id')
		->leftJoin('user', 'user_groups.id = user.group_id')
		->where('user.id = :id AND permissions.controller_id = :controller_id', [':id' => $userID, 'controller_id' => $controllerID])
		->one();
	}
	
	public function getPermissionsDataByUserID($userID)
    {
		return self::find()
		->select('permissions.controller_id, permissions.id')
		->from('permissions')
		->leftJoin('user_groups', 'user_groups.id = permissions.group_id')
		->leftJoin('user', 'user_groups.id = user.group_id')
		->where('user.id = :id', [':id' => $userID])
		->asArray()
		->all();
	}
	
	public function getPermittedMenuList($userID)
    {
		$result = [];
		$adminMenuList = AdminMenu::find()->select(['id', 'url'])->asArray()->all();
		
		if(!empty($adminMenuList))
		{
			$controllersArr = Yii::$app->params['controllers'];
			
			if(!empty($controllersArr))
			{
				$controllersArr = array_flip(array_combine(array_keys($controllersArr), array_column($controllersArr, 'controller')));
				$adminMenuCamelCaseList = [];
				$adminMenuList = ArrayHelper::map($adminMenuList, 'url', 'id');
			
				foreach($adminMenuList as $key=>$value)
				{
					$adminMenuCamelCaseList[ucfirst(Service::dashesToCamelCase($key))] = $value;
				}
				
				$adminMenuListDiff = array_flip(array_diff_assoc($controllersArr, $adminMenuCamelCaseList));
				
				if(!empty($adminMenuListDiff))
				{
					$permissionsData = $this->getPermissionsDataByUserID($userID);
					$permissionsData = ArrayHelper::map($permissionsData, 'controller_id', 'id');
					
					$result = array_flip(array_intersect_key($adminMenuListDiff, $permissionsData));
				}
			}
		}
		
		return $result;
	}
}
