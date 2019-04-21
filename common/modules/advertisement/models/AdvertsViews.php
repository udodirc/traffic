<?php

namespace common\modules\advertisement\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "adverts_views".
 *
 * @property integer $id
 * @property integer $advert_id
 * @property integer $partner_id
 * @property string $user_ip
 * @property integer $type
 * @property integer $balls
 * @property integer $created_at
 *
 * @property Partners $partner
 * @property TextAdvert $advert
 */
class AdvertsViews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adverts_views';
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
				'value' => function() { return date('U');},
			],
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advert_id', 'partner_id', 'user_ip', 'type', 'balls', 'created_at'], 'required'],
            [['advert_id', 'partner_id', 'type', 'balls', 'created_at'], 'integer'],
            [['user_ip'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'advert_id' => Yii::t('form', 'Advert ID'),
            'partner_id' => Yii::t('form', 'Partner ID'),
            'user_ip' => Yii::t('form', 'User Ip'),
            'type' => Yii::t('form', 'Type'),
            'balls' => Yii::t('form', 'Balls'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
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
    public function getAdvert()
    {
        return $this->hasOne(TextAdvert::className(), ['id' => 'advert_id']);
    }
}
