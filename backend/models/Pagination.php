<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pagination".
 *
 * @property integer $id
 * @property string $url
 * @property integer $value
 */
class Pagination extends \yii\db\ActiveRecord
{
	public $menu_name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pagination';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['menu_id'], 'integer'],
            ['menu_id', 'unique', 'targetAttribute' => 'menu_id', 'message' => 'Такое меню уже есть'],
            [['value'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'menu_id' => Yii::t('form', 'Меню'),
            'value' => Yii::t('form', 'Значение'),
        ];
    }
    
    public function getAdminMenu()
    {
        return $this->hasMany(AdminMenu::className(), ['menu_id' => 'id']);
    }
    
    /**
     * Add menu id before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {
		if(parent::beforeSave($insert)) 
		{
			if($this->isNewRecord) 
			{
				$this->menu_id = ($this->menu_id === '' || $this->menu_id == NULL) ? 0 : $this->menu_id;
			}
		}
		
		return parent::beforeSave($insert);
	}
    
    public function getPaginationDataByID($id)
    {
		return self::find()
		->select('admin_menu.name AS menu_name, pagination.id, pagination.value as value')
		->from('pagination')
		->leftJoin('admin_menu', 'admin_menu.id = pagination.menu_id')
		->where('pagination.id=:id', [':id' => $id])
		->one();
	}
}
