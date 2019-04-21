<?php
namespace common\modules\payeer\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "payment_logs".
 *
 * @property int $id
 * @property int $partner_id
 * @property int $structure
 * @property int $matrix
 * @property int $places
 * @property int $order_id
 * @property number $amount
 * @property string $currency
 * @property int $operation_id
 * @property datetime $operation_date
 * @property datetime $operation_pay_date
 *
 * @property Partners $partner
 */
class PayeerPayments extends \yii\db\ActiveRecord
{
	const PAYMENT_TYPE_ALL = 0;
	const PAYMENT_TYPE_PAY = 1;
    const PAYMENT_TYPE_PAY_OFF = 2;
    
    const CURRENCY_ALL = '';
	const CURRENCY_USD = 'USD';
	const CURRENCY_EUR = 'EUR';
    const CURRENCY_RUB = 'RUB';
	
	public $login;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payeer_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'places', 'order_id', 'amount', 'currency', 'operation_id', 'operation_date', 'operation_pay_date'], 'required'],
            [['partner_id', 'structure_number', 'matrix_number', 'places', 'matrix_id', 'order_id', 'type', 'operation_id', 'operation_date', 'operation_pay_date'], 'integer'],
            [['amount'], 'number'],
            [['currency'], 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'login' => Yii::t('form', 'Логин'),
            'partner_id' => Yii::t('form', 'Partner ID'),
			'matrix_id' => Yii::t('form', 'ID Матрицы'),
            'structure_number' => Yii::t('form', 'Структура'),
            'matrix_number' => Yii::t('form', 'Номер матрицы'),
            'places' => Yii::t('form', 'Мест'),
            'order_id' => Yii::t('form', 'ID заказа'),
            'type' => Yii::t('form', 'Тип оплаты'),
            'amount' => Yii::t('form', 'Сумма'),
            'currency' => Yii::t('form', 'Валюта'),
            'operation_id' => Yii::t('form', 'ID операции'),
            'operation_date' => Yii::t('form', 'Дата операции:'),
            'operation_pay_date' => Yii::t('form', 'Дата оплаты:'),
            'date_from' => Yii::t('form', 'Дата от:'),
            'date_to' => Yii::t('form', 'Дата до:'),
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
    
    public static function insertPayments($partnerID, $structureNumber, $matrixNumber, $matrixID, $places, $orderID, $type, $amount, $currency, $operationID, $operationDate, $operationPayDate)
    {
		$result = false;
		$model = new PayeerPayments();
								
		$model->partner_id = $partnerID;
		$model->structure_number = $structureNumber;
		$model->matrix_number = $matrixNumber;
		$model->matrix_id = $matrixID;
		$model->places = $places;
		$model->order_id = $orderID;
		$model->type = $type;
		$model->amount = $amount;
		$model->currency = $currency;
		$model->operation_id = $operationID;
		$model->operation_date = $operationDate;
		$model->operation_pay_date = $operationPayDate;
		
		$result = $model->save(false);	
		
		return $result;
	}
	
	public static function getPaymentTypeList()
    {
		return [
			self::PAYMENT_TYPE_ALL => Yii::t('form', 'Все'),
			self::PAYMENT_TYPE_PAY => Yii::t('form', 'Оплата'), 
			self::PAYMENT_TYPE_PAY_OFF => Yii::t('form', 'Выплата'),
		];
	}
	
	public static function getCurrencyList()
    {
		return [
			self::CURRENCY_ALL => Yii::t('form', 'Все'),
			self::CURRENCY_USD => Yii::t('form', 'USD'), 
			self::CURRENCY_EUR => Yii::t('form', 'EUR'),
			self::CURRENCY_RUB => Yii::t('form', 'RUB'),
		];
	}
}
