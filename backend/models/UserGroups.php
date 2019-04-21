<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "user_groups".
 *
 * @property integer $id
 * @property string $name
 */
class UserGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'min' => 2, 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'name' => Yii::t('form', 'Имя группы'),
        ];
    }
    
    /**
     * Relation wit user table by group_id field
     */
    public function getUsers()
    {
        return $this->hasOne(User::className(), ['group_id' => 'id']);
    }
    
    public function getPermissions()
    {
        return $this->hasMany(Permissions::className(), ['group_id' => 'id']);
    }
    
    public static function getGroupsList()
    {
		return ArrayHelper::map(UserGroups::find()->asArray()->all(), 'id', 'name');
	}
}
