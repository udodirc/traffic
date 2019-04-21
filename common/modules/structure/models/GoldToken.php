<?php
namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "gold_token".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $matrix_id
 * @property integer $matrix
 * @property string $amount
 * @property integer $created_at
 *
 * @property Partners $partner
 */
class GoldToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gold_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'matrix_id', 'structure_number', 'matrix', 'amount', 'created_at'], 'required'],
            [['partner_id', 'matrix_id', 'structure_number', 'matrix', 'created_at'], 'integer'],
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
            'matrix_id' => Yii::t('form', 'ID матрицы'),
            'structure_number' => Yii::t('form', 'Структура'),
            'matrix' => Yii::t('form', 'Матрица'),
            'amount' => Yii::t('form', 'Сумма'),
            'created_at' => Yii::t('form', 'Дата ативации'),
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
    public function getPartnerName()
	{
		$partner = $this->partner;
		
		return $partner ? $partner->login : '';
	}
}
