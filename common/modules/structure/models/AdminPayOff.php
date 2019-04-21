<?php
namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "admin_pay_off".
 *
 * @property int $id
 * @property int $partner_id
 * @property int $structure_number
 * @property int $matrix_number
 * @property int $matrix_id
 * @property string $amount
 * @property int $created_at
 *
 * @property Partners $partner
 */
class AdminPayOff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_pay_off';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'amount', 'created_at'], 'required'],
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'created_at'], 'integer'],
            [['amount'], 'number'],
            //[['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::className(), 'targetAttribute' => ['partner_id' => 'id']],
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
            'matrix_number' => Yii::t('form', 'Матрица'),
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
