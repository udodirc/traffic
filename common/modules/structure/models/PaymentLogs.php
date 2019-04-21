<?php

namespace common\modules\structure\models;

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
 * @property int $account_type
 * @property string $sci_sign
 * @property int $order_id
 * @property string $amount
 * @property string $transact_id
 * @property int $type
 *
 * @property Partners $partner
 */
class PaymentLogs extends \yii\db\ActiveRecord
{
	public $login;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'structure_number', 'matrix', 'matrix_id', 'places', 'account_type', 'sci_sign', 'order_id', 'amount', 'transact_id', 'action_type', 'type'], 'required'],
            [['partner_id', 'structure_number', 'matrix', 'places', 'matrix_id', 'account_type', 'order_id', 'action_type', 'type', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['sci_sign', 'transact_id'], 'string', 'max' => 20],
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
            'login' => Yii::t('form', 'Логин'),
            'partner_id' => Yii::t('form', 'Partner ID'),
			'matrix_id' => Yii::t('form', 'ID Матрицы'),
            'structure_number' => Yii::t('form', 'Структура'),
            'matrix_number' => Yii::t('form', 'Номер матрицы'),
            'places' => Yii::t('form', 'Мест'),
            'account_type' => Yii::t('form', 'Тип оплаты'),
            'action_type' => Yii::t('form', 'Тип действия'),
            'sci_sign' => Yii::t('form', 'Sci'),
            'order_id' => Yii::t('form', 'ID заказа'),
            'amount' => Yii::t('form', 'Сумма'),
            'transact_id' => Yii::t('form', 'ID транзакции'),
            'date_from' => Yii::t('form', 'Дата от:'),
            'date_to' => Yii::t('form', 'Дата до:'),
            'type' => Yii::t('form', 'Тип лога'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
    
    public function insertLogs($partnerID, $structureNumber, $matrixNumber, $matrixID, $places, $paymentType, $sciSign, $orderID, $amount, $transactID, $actionType, $type)
    {
		$result = false;
		$model = new PaymentLogs();
								
		$model->partner_id = $partnerID;
		$model->structure_number = $structureNumber;
		$model->matrix_number = $matrixNumber;
		$model->matrix_id = $matrixID;
		$model->places = $places;
		$model->account_type = $paymentType;
		$model->sci_sign = $sciSign;
		$model->order_id = $orderID;
		$model->amount = $amount;
		$model->transact_id = $transactID;
		$model->action_type = $actionType;
		$model->type = $type;
		$model->created_at = time();
		
		$result = $model->save(false);	
		
		return $result;
	}
	
	public function getIDByLogin($login)
    {
		$result = self::find()
		->select('`payment_logs`.`partner_id`')
		->leftJoin('partners', '`partners`.`id` = `payment_logs`.`partner_id`')
		->where('`partners`.`login` = :login', [':login' => $login])
		->limit(1)
		->one();
		
		return $result;
	}
	
	public function getLogBydID($id)
    {
		$result = self::find()
		->select('`payment_logs`.`id`, `payment_logs`.`structure_number`, `payment_logs`.`matrix_id`, `payment_logs`.`matrix_number`, `payment_logs`.`amount`, 
		`payment_logs`.`created_at`, `payment_logs`.`account_type`, `payment_logs`.`places`, `payment_logs`.`sci_sign`, `payment_logs`.`order_id`,
		`payment_logs`.`transact_id`, `payment_logs`.`action_type`, `payment_logs`.`type`, `partners`.`login`')
		->leftJoin('partners', '`partners`.`id` = `payment_logs`.`partner_id`')
		->where('`payment_logs`.`id` = :id', [':id' => $id])
		->one();
		
		return $result;
	}
}
