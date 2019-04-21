<?php
namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "demo_matrix_payments".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $matrix_number
 * @property integer $matrix_id
 * @property integer $type
 * @property string $amount
 * @property integer $created_at
 *
 * @property Partners $partner
 */
class DemoMatrixPayments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'demo_matrix_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'matrix_number', 'matrix_id', 'type', 'amount', 'created_at'], 'required'],
            [['partner_id', 'matrix_number', 'matrix_id', 'type', 'paid_off', 'created_at'], 'integer'],
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
            'matrix_number' => Yii::t('form', 'Номер матрицы'),
            'matrix_id' => Yii::t('form', 'ID матрицы'),
            'type' => Yii::t('form', 'Type'),
            'amount' => Yii::t('form', 'Сумма'),
            'created_at' => Yii::t('form', 'Дата'),
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
    public function getPayerPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'payer_partner_id'])->from(['p2' => Partners::tableName()]);
    }
}
