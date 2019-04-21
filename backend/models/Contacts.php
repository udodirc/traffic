<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property integer $id
 * @property integer $field_type
 * @property integer $name
 * @property string $desc
 * @property integer $status
 */
class Contacts extends \yii\db\ActiveRecord
{
	public $field;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_type', 'name', 'desc', 'status'], 'required'],
            [['field_type', 'name', 'status'], 'integer'],
            [['desc'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'field_type' => Yii::t('form', 'Field Type'),
            'name' => Yii::t('form', 'Name'),
            'desc' => Yii::t('form', 'Desc'),
            'status' => Yii::t('form', 'Status'),
        ];
    }
    
    public static function getContactFieldList()
    {
		$contactArr = Yii::$app->params['contact_fields'];
		$contactArr = array_column($contactArr, 'name');
		array_unshift($contactArr, "");
		unset($contactArr[0]);
		
		return $contactArr;
	}
}
