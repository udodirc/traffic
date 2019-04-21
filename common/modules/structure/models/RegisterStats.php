<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "register_stats".
 *
 * @property string $register_date
 * @property integer $count
 */
class RegisterStats extends \yii\db\ActiveRecord
{
	public $register_stats;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'register_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['register_date', 'count'], 'required'],
            [['register_date'], 'safe'],
            [['count', 'register_stats'], 'integer'],
            [['register_date'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'register_date' => Yii::t('form', 'Register Date'),
            'count' => Yii::t('form', 'Count'),
        ];
    }
    
    public static function getRegisterCountByCurrentDay()
    {
		return self::find()
		->select('`count` AS `register_stats`')
		->where('`register_date` = CURDATE()')
		->one();
	}
}
