<?php
namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;
use common\modules\payeer\models\Payeer;

/**
 * This is the model class for table "auto_pay_off_logs".
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
class AutoPayOffLogs extends \yii\db\ActiveRecord
{
	public $login;
	public $wallet;
	
	const PAYMENT_TYPE_ALL = 0;
	const PAYMENT_TYPE_PAY = 1;
    const PAYMENT_TYPE_PAY_OFF = 2;
    
    const PAY_OFF = 1;
    const NOT_PAY_OFF = 0;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_pay_off_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'type', 'amount', 'created_at'], 'required'],
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'type', 'paid_off', 'created_at'], 'integer'],
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
            'type' => Yii::t('form', 'Тип оплаты'),
            'paid_off' => Yii::t('form', 'Выплата'),
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
    public function getPartnerName()
	{
		$partner = $this->partner;
		
		return $partner ? $partner->login : '';
	}
    
    public static function getPaymentsList($wallet)
    {
		$result = null;
		
		if($wallet != '')
		{
			$result = self::find()
			->select(['`auto_pay_off_logs`.*', '`partners`.`login` AS `login`', '`partners`.`'.$wallet.'`'])
			->leftJoin('partners', 'partners.id = `auto_pay_off_logs`.`partner_id`')
			->where(['`auto_pay_off_logs`.`paid_off`' => '0'])
			->all();
		}
		
		return $result;
	}
	
	public static function makeAutoPayment()
    {
		$result = false;
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes))
		{
			$paymentType = Payment::getPaymentType();
			
			if(isset($paymentsTypes[$paymentType][1]))
			{	
				$wallet = $paymentsTypes[$paymentType][1];
				$paymentsList = self::getPaymentsList($wallet);
				
				foreach($paymentsList as $key => $paymentData)
				{	
					if($paymentData->id > 0 && $paymentData->partner_id > 0 && $paymentData->structure_number > 0 && $paymentData->matrix_number > 0 && $paymentData->matrix_id > 0 && $paymentData->type > 0 && $paymentData->amount > 0 && $paymentData->$wallet != '')
					{	
						switch($paymentsTypes[$paymentType][1])
						{
							case 'payeer_wallet':
							
								$result = Payeer::makeAutoPayment($paymentData->login, $paymentData->id,  $paymentData->partner_id, $paymentData->structure_number, $paymentData->matrix_number, $paymentData->matrix_id, $paymentData->type, $paymentData->amount, $paymentData->$wallet, true);
							
							break;
								
							default:
			
								$result = false;
								
							break;
						}
						
						if(!$result)
						{
							break;
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function getPaymentData($id, $wallet)
    {
		return self::find()
		->select(['`auto_pay_off_logs`.`id`', '`auto_pay_off_logs`.`partner_id`', '`auto_pay_off_logs`.`structure_number`', '`auto_pay_off_logs`.`matrix_number`', '`auto_pay_off_logs`.`matrix_id`', '`auto_pay_off_logs`.`amount`', '`partners`.`login` AS `login`', '`partners`.`'.$wallet.'` AS `wallet`'])
		->leftJoin('`partners`', '`partners`.`id` = `auto_pay_off_logs`.`partner_id`')
		->where(['`auto_pay_off_logs`.`id`' => $id])
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
    
    public static function getPaymentTypeList()
    {
		return [
			self::PAYMENT_TYPE_ALL => Yii::t('form', 'Все'),
			self::PAYMENT_TYPE_PAY => Yii::t('form', 'Выплата за матрицу'), 
			self::PAYMENT_TYPE_PAY_OFF => Yii::t('form', 'Выплата за личные приглашения'),
		];
	}
	
	public static function getPayOffList()
    {
		return [
			self::PAY_OFF => Yii::t('form', 'Выплачено'), 
			self::NOT_PAY_OFF => Yii::t('form', 'Не выплачено'),
		];
	}
}
