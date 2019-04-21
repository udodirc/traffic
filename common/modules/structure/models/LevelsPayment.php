<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "levels_payment".
 *
 * @property integer $id
 * @property integer $matrix_id
 * @property integer $partner_id
 * @property integer $refferal_id
 * @property integer $level
 * @property string $amount
 * @property integer $created_at
 *
 * @property Matrix1 $matrix
 * @property Partners $partner
 * @property Partners $refferal
 */
class LevelsPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'levels_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matrix_id', 'partner_id', 'refferal_id', 'level', 'amount', 'created_at'], 'required'],
            [['matrix_id', 'partner_id', 'refferal_id', 'level', 'created_at'], 'integer'],
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
            'matrix_id' => Yii::t('form', 'Matrix ID'),
            'partner_id' => Yii::t('form', 'Partner ID'),
            'refferal_id' => Yii::t('form', 'Refferal ID'),
            'level' => Yii::t('form', 'Level'),
            'amount' => Yii::t('form', 'Amount'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatrix()
    {
        return $this->hasOne(Matrix1::className(), ['id' => 'matrix_id']);
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
    public function getRefferal()
    {
        return $this->hasOne(Partners::className(), ['id' => 'refferal_id']);
    }
}
