<?php
namespace common\modules\structure\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\modules\structure\models\MatrixPayments;

/**
 * This is the model class for table "payments_invoices".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $matrix_number
 * @property integer $matrix_id
 * @property integer $paid_matrix_partner_id
 * @property integer $paid_matrix_id
 * @property integer $payment_type
 * @property integer $account_type
 * @property string $amount
 * @property string $transact_id
 * @property integer $created_at
 *
 * @property Partners $paidMatrixPartner
 * @property Partners $partner
 */
class PaymentsInvoices extends \yii\db\ActiveRecord
{
	public $login_receiver;
	public $login_paid;
	public $date_from;
	public $date_to;
	public $wallet;
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments_invoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'matrix_number', 'matrix_id', 'paid_matrix_partner_id', 'paid_matrix_id', 'payment_type', 'account_type', 'amount', 'transact_id', 'created_at'], 'required'],
            [['partner_id', 'structure_number', 'matrix_number', 'matrix_id', 'paid_matrix_partner_id', 'paid_matrix_id', 'payment_type', 'account_type', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['transact_id'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => Yii::t('form', 'ID'),
            'matrix_id' => Yii::t('form', 'ID Матрицы получателя'),
            'structure_number' => Yii::t('form', 'Структура'),
            'matrix_number' => Yii::t('form', 'Номер матрицы'),
            'partner_id' => Yii::t('form', 'Partner ID'),
            'payment_type' => Yii::t('form', 'Платежная система'),
            'amount' => Yii::t('form', 'Сумма'),
            'note' => Yii::t('form', 'Сообщение'),
            'login_receiver' => Yii::t('form', 'Получатель'),
            'login_paid' => Yii::t('form', 'Отправитель'),
            'paid_matrix_partner_id' => Yii::t('form', 'Paid Matrix Partner ID'),
            'paid_matrix_id' => Yii::t('form', 'ID Матрицы отправителя'),
            'payment_type' => Yii::t('form', 'Payment Type'),
            'account_type' => Yii::t('form', 'Account Type'),
            'transact_id' => Yii::t('form', 'ID транзакции'),
            'date_from' => Yii::t('form', 'Дата от:'),
            'date_to' => Yii::t('form', 'Дата до:'),
            'wallet' => Yii::t('form', 'Кошелек'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaidMatrixPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'paid_matrix_partner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }
    
    public function getInvoicesList()
    {
		$result = self::find()
		->select('`payments_invoices`.`id`, `payments_invoices`.`structure_number`, `payments_invoices`.`matrix_number`, `payments_invoices`.`matrix_id`, `payments_invoices`.`amount`, 
		`payments_invoices`.`account_type`, `partners`.`login` AS `login_receiver`, `partners2`.`login` AS `login_paid`')
		->leftJoin('partners', '`partners`.`id` = `payments_invoices`.`partner_id`')
		->leftJoin('partners partners2', '`partners2`.`id` = `payments_invoices`.`paid_matrix_partner_id`')
		->orderBy('`payments_invoices`.`created_at` DESC, `payments_invoices`.`id` DESC');
		
		return $result;
	}
	
	public function getInvoicesListByPartnerID($id, $accountType)
    {
		$result = self::find()
		->select('`payments_invoices`.`id`, `payments_invoices`.`structure_number`, `payments_invoices`.`matrix_number`, `payments_invoices`.`matrix_id`, `payments_invoices`.`amount`, 
		`payments_invoices`.`account_type`, `partners`.`login` AS `login_receiver`, `partners2`.`login` AS `login_paid`')
		->leftJoin('partners', '`partners`.`id` = `payments_invoices`.`partner_id`')
		->leftJoin('partners partners2', '`partners2`.`id` = `payments_invoices`.`paid_matrix_partner_id`')
		->where('`partners`.`id` = :id AND `payments_invoices`.`account_type` = :account_type', [':id' => $id, ':account_type' => $accountType])
		->orderBy('`payments_invoices`.`created_at` DESC, `payments_invoices`.`id` DESC');
		
		return $result;
	}
	
	public function getInvoiceBydID($id)
    {
		$result = self::find()
		->select('`payments_invoices`.`id`, `payments_invoices`.`matrix_id`, `payments_invoices`.`structure_number`, `payments_invoices`.`matrix_number`, `payments_invoices`.`matrix_id`, 
		`payments_invoices`.`amount`, `payments_invoices`.`paid_matrix_id`, `payments_invoices`.`created_at`, `payments_invoices`.`payment_type`, 
		`payments_invoices`.`account_type`, `payments_invoices`.`transact_id`, `partners`.`login` AS `login_receiver`, `partners2`.`login` AS `login_paid`')
		->leftJoin('partners', '`partners`.`id` = `payments_invoices`.`partner_id`')
		->leftJoin('partners partners2', '`partners2`.`id` = `payments_invoices`.`paid_matrix_partner_id`')
		->where('`payments_invoices`.`id` = :id', [':id' => $id])
		->one();
		
		return $result;
	}
	
	public function getIDByLoginReceiver($login)
    {
		$result = self::find()
		->select('`payments_invoices`.`partner_id`')
		->leftJoin('partners', '`partners`.`id` = `payments_invoices`.`partner_id`')
		->where('`partners`.`login` = :login', [':login' => $login])
		->limit(1)
		->one();
		
		return $result;
	}
	
	public function getIDByLoginPaid($login)
    {
		$result = self::find()
		->select('`payments_invoices`.`paid_matrix_partner_id`')
		->leftJoin('partners', '`partners`.`id` = `payments_invoices`.`paid_matrix_partner_id`')
		->where('`partners`.`login` = :login', [':login' => $login])
		->limit(1)
		->one();
		
		return $result;
	}
	
	public static function getIDByWallet($wallet)
    {
		$result = self::find()
		->select('`partners`.`id`')
		->leftJoin('partners', '`partners`.`id` = `payments_invoices`.`partner_id`')
		->where('`partners`.`qiwi_wallet` = :wallet OR `partners`.`blockchain` = :wallet OR `partners`.`perfect_wallet` = :wallet OR `partners`.`advcash_wallet` = :wallet', [':wallet' => $wallet])
		->asArray()
		->all();
		
		return $result;
	}
	
	public function comparePaymentsWithMatrixPayments($id)
    {
		$result = [];
		$paymentInvoicesList = self::find()
		->select('`matrix_number`, `matrix_id`, `paid_matrix_id`, `amount`')
		->where('`partner_id` = :id AND `account_type` = :type', [':id' => $id, ':type' => 2])
		->asArray()
		->all();
		$matrixPaymentList = MatrixPayments::find()
		->select('`matrix_number`, `matrix_id`, `amount`')
		->where('`partner_id` = :id AND `type` = :type', [':id' => $id, ':type' => 2])
		->asArray()
		->all();
		
		echo '<pre>';
		print_r($paymentInvoicesList);
		echo '</pre>';
		
		/*echo '<pre>';
		print_r($matrixPaymentList);
		echo '</pre>';*/
		
		$paymentInvoicesList = self::getPaymentsListByMatrixID($paymentInvoicesList);
		//$matrixPaymentList = ArrayHelper::map($matrixPaymentList, 'matrix_id', 'amount', 'matrix_number');
		//$matrixPaymentList = ArrayHelper::map($matrixPaymentList, 'matrix_id', 'amount', 'matrix_number');
		
		echo '<pre>';
		print_r($paymentInvoicesList);
		echo '</pre>';
		
		echo '<pre>';
		print_r($matrixPaymentList);
		echo '</pre>';
		
		/*foreach($matrixPaymentList as $matrix => $matrixData)
		{
			if(!isset($paymentInvoicesList[$matrix]))
			{
				echo $matrix.'<br/>';
				print_r($matrixData);
			}
		}*/
		
		
	}
	
	public static function getPaymentsListByMatrixID($paymentInvoicesList)
	{
		$result = [];
		
		foreach($paymentInvoicesList as $key => $paymentData)
		{
			$result[$paymentData['matrix_number']][$paymentData['matrix_id']][$paymentData['paid_matrix_id']] = $paymentData['amount'];
		}
		
		return $result;
	}
	
	public function insertInvoice($matrixID, $structureNumber, $matrixNumber, $partnerID, $paidMatrixpartnerID, $paidMatrixID, $paymentType, $amountType, $amount, $transactID)
    {
		$this->partner_id = $partnerID;
		$this->structure_number = $structureNumber;
		$this->matrix_number = $matrixNumber;
		$this->matrix_id = $matrixID;
		$this->paid_matrix_partner_id = $paidMatrixpartnerID;
		$this->paid_matrix_id = $paidMatrixID;
		$this->payment_type = $paymentType;
		$this->account_type = $amountType;
		$this->amount = $amount;
		$this->transact_id = $transactID;
		$this->created_at = time();
		
		return ($this->save(false)) ? true : false;
	}
}
