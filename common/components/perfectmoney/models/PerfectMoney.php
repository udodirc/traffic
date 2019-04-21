<?php

namespace common\components\perfectmoney\models;

use Yii;

/**
 * This is the model class for table "perfect_money".
 *
 * @property integer $payment_id
 * @property string $payee_account
 * @property string $payment_amount
 * @property string $payment_units
 * @property integer $payment_butch_num
 * @property string $payer_account
 * @property integer $timestampgmt
 */
class PerfectMoney extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'perfect_money';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payee_account', 'payment_amount', 'payment_units', 'payment_butch_num', 'payer_account', 'timestampgmt'], 'required'],
            [['payment_amount'], 'number'],
            [['payment_butch_num', 'timestampgmt'], 'integer'],
            [['payee_account', 'payer_account'], 'string', 'max' => 12],
            [['payment_units'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payment_id' => Yii::t('form', 'Payment ID'),
            'payee_account' => Yii::t('form', 'Payee Account'),
            'payment_amount' => Yii::t('form', 'Payment Amount'),
            'payment_units' => Yii::t('form', 'Payment Units'),
            'payment_butch_num' => Yii::t('form', 'Payment Butch Num'),
            'payer_account' => Yii::t('form', 'Payer Account'),
            'timestampgmt' => Yii::t('form', 'Timestampgmt'),
        ];
    }
}
