<?php

namespace app\models;

use yii\web\User as BaseUser;

class User extends BaseUser
{
	public static function checkAccess($userID)
    {
       
        echo $userID;
        
        return true;
    }
}

?>
