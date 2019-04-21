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
class MatrixPayments extends \yii\db\ActiveRecord
{
	public $login;
	public $wallet;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matrix_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'type', 'amount', 'created_at'], 'required'],
            [['partner_id', 'structure_number', 'matrix_id', 'type', 'paid_off', 'created_at'], 'integer'],
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
            'structure_number' => Yii::t('form', 'Структура'),
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
    
    public static function getPaymentData($id, $wallet)
    {
		return self::find()
		->select(['`matrix_payments`.`id`', '`matrix_payments`.`partner_id`', '`matrix_payments`.`structure_number`', '`matrix_payments`.`matrix_number`', '`matrix_payments`.`matrix_id`', '`matrix_payments`.`amount`', '`partners`.`login` AS `login`', '`partners`.`'.$wallet.'` AS `wallet`'])
		->leftJoin('`partners`', '`partners`.`id` = `matrix_payments`.`partner_id`')
		->where(['`matrix_payments`.`id`' => $id])
		->one();
	}
    
    public static function updatePaidOff($id)
    {
		$result = false;
        $model = self::find()->where('id=:id', [':id' => $id])->one();
						
		if($model->paid_off == 0)
		{
			$model->paid_off = 1;
			$result = $model->save(false);
		}
		
		return $result;
    }
}
