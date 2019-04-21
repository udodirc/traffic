<?php
namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "invite_pay_off".
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
class InvitePayOff extends \yii\db\ActiveRecord
{
	public $benefit_login;
	public $payer_login;
	public $wallet;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invite_pay_off';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'amount', 'created_at'], 'required'],
            [['partner_id', 'benefit_partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'paid_off', 'created_at'], 'integer'],
            [['benefit_login', 'payer_login'], 'string'],
            [['amount'], 'number'],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::className(), 'targetAttribute' => ['partner_id' => 'id']],
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
            'payer_login' => Yii::t('form', 'Логин плательщика'),
            'benefit_login' => Yii::t('form', 'Логин получателя'),
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
    public function getBenefitPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'benefit_partner_id'])->from(['p2' => Partners::tableName()]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerName()
	{
		$partner = $this->partner;
		
		return $partner ? $partner->login : '';
	}
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getBenfitPartnerName()
	{
		$partner = $this->benefitPartner;
		
		return $partner ? $partner->login : '';
	}
	
	public static function getPaymentData($id, $wallet)
    {
		return self::find()
		->select(['`invite_pay_off`.`id`', '`invite_pay_off`.`partner_id`', '`invite_pay_off`.`structure_number`', '`invite_pay_off`.`matrix_number`', '`invite_pay_off`.`matrix_id`', '`invite_pay_off`.`amount`', '`partners`.`login` AS `login`', '`benefit_partner`.`'.$wallet.'` AS `wallet`'])
		->leftJoin('`partners`', '`partners`.`id` = `invite_pay_off`.`partner_id`')
		->leftJoin('`partners` `benefit_partner`', '`benefit_partner`.`id` = `invite_pay_off`.`benefit_partner_id`')
		->where(['`invite_pay_off`.`id`' => $id])
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
