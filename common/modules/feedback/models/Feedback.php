<?php

namespace common\modules\feedback\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $feedback
 * @property integer $created_at
 *
 * @property Partners $partner
 */
class Feedback extends \yii\db\ActiveRecord
{
	public $username;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
			],
		];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feedback'], 'required'],
            [['partner_id', 'created_at'], 'integer'],
            [['feedback'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'partner_id' => Yii::t('form', 'Партнер'),
            'feedback' => Yii::t('form', 'Отзыв'),
            'created_at' => Yii::t('form', 'Дата создания'),
        ];
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
				$this->partner_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
			}
		}
		
		return parent::beforeSave($insert);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutorName()
	{
		$author = $this->partner;
		
		return $author ? $author->login : '';
	}
}
