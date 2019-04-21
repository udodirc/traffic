<?php

namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "balls".
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
class Balls extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'referral_id', 'matrix_number', 'matrix_id', 'type', 'balls', 'created_at'], 'required'],
            [['partner_id', 'referral_id', 'structure_number', 'matrix_number', 'matrix_id', 'type', 'created_at'], 'integer'],
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
            'structure_number' => Yii::t('form', 'Cтруктура'),
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerName()
	{
		$partner = $this->partner;
		
		return $partner ? $partner->login : '';
	}
    
    public function getPartnersBallsList()
    {
        return Partners::find()
		->select('`partners`.`id`, `partners`.`login`, `partners`.`email`, SUM(`balls_1`.`balls`) AS `referral_balls`, SUM(`balls_2`.`balls`) AS `close_balls`')
		->leftJoin('`balls` `balls_1`', '`partners`.`id` = `balls_1`.`partner_id` AND `balls_1`.`type` = 1')
		->leftJoin('`balls` `balls_2`', '`partners`.`id` = `balls_2`.`partner_id` AND `balls_2`.`type` = 2')
		->groupBy('`partners`.`id`')
		->having('SUM(`balls_1`.`balls`) > 0 OR SUM(`balls_2`.`balls`) > 0');
    }
}
