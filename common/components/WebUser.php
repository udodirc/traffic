<?php
namespace common\components;

use Yii;
use yii\web\User as BaseUser;
use common\models\Permissions;
use common\models\Service;

class WebUser extends BaseUser
{
	public function can($permissionType, $params = [], $allowCaching = true)
    {	
		$userID = Yii::$app->user->id;
		
		if($userID == '1' || (Permissions::checkPermissionsByUrl()))
		{
			return true;
		}
		
		if(!empty($permissionType))
		{
			$controllerID = Service::getControllerID(Yii::$app->controller->id);
		
			if($userID > 0 && $controllerID > 0) 
			{
				$permissionModel = new Permissions();
				$permissions = $permissionModel->getPermissionsDataByControllerID($userID, $controllerID);
				
				if($permissions !== NULL)
				{
					$permissions = unserialize($permissions->permissions);
					$permissionType .= '_perm';
					$permission = (isset($permissions[$permissionType])) ? $permissions[$permissionType] : 0;
					
					if($permission > 0)
					{
						return true;
					}
				}
			}
		}
        
        return false;
    }
}

?>
