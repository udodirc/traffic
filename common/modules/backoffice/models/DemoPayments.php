<?php

namespace common\modules\backoffice\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "demo_payments".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $refferal_id
 * @property integer $level
 * @property integer $payment_type
 * @property string $amount
 * @property integer $created_at
 *
 * @property Partners $refferal
 * @property Partners $partner
 */
class DemoPayments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'demo_payments';
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
            [['partner_id', 'refferal_id', 'level', 'payment_type', 'amount', 'created_at'], 'required'],
            [['partner_id', 'refferal_id', 'level', 'payment_type', 'created_at'], 'integer'],
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'partner_id' => Yii::t('form', 'Partner ID'),
            'refferal_id' => Yii::t('form', 'Refferal ID'),
            'level' => Yii::t('form', 'Level'),
            'payment_type' => Yii::t('form', 'Payment Type'),
            'amount' => Yii::t('form', 'Amount'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefferal()
    {
        return $this->hasOne(Partners::className(), ['id' => 'refferal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
}
