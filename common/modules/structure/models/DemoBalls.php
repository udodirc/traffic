<?php

namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "demo_balls".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $referral_id
 * @property integer $matrix_number
 * @property integer $matrix_id
 * @property integer $type
 * @property string $balls
 * @property integer $created_at
 *
 * @property Partners $partner
 * @property Partners $referral
 */
class DemoBalls extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'demo_balls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'referral_id', 'matrix_number', 'matrix_id', 'type', 'balls', 'created_at'], 'required'],
            [['partner_id', 'referral_id', 'matrix_number', 'matrix_id', 'type', 'created_at'], 'integer'],
            [['balls'], 'number']
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
            'referral_id' => Yii::t('form', 'Referral ID'),
            'matrix_number' => Yii::t('form', '№ матрицы'),
            'matrix_id' => Yii::t('form', 'ID матрицы'),
            'type' => Yii::t('form', 'Type'),
            'balls' => Yii::t('form', 'Баллы'),
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
    public function getReferral()
    {
        return $this->hasOne(Partners::className(), ['id' => 'referral_id']);
    }
}
