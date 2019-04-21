<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "matrices_settings".
 *
 * @property integer $number
 * @property integer $type
 * @property integer $levels
 * @property integer $clone
 * @property integer $clone_cycle
 * @property string $pay
 * @property string $admin_pay_off
 * @property string $pay_off
 * @property string $clone_pay
 * @property string $clone_admin_pay_off
 * @property string $clone_pay_off
 * @property integer $close_matrix_balls
 * @property integer $referral_matrix_balls
 * @property integer $account_type
 * @property integer $close_matrix
 */
class MatricesSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matrices_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'type', 'levels', 'clone', 'clone_cycle', 'pay', 'admin_pay_off', 'pay_off', 'clone_pay', 'clone_admin_pay_off', 'clone_pay_off', 'close_matrix_balls', 'referral_matrix_balls', 'account_type', 'close_matrix'], 'required'],
            [['number', 'type', 'levels', 'clone', 'clone_cycle', 'close_matrix_balls', 'referral_matrix_balls', 'account_type', 'close_matrix'], 'integer'],
            [['pay', 'admin_pay_off', 'pay_off', 'clone_pay', 'clone_admin_pay_off', 'clone_pay_off'], 'number'],
            [['number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'number' => Yii::t('form', 'Number'),
            'type' => Yii::t('form', 'Type'),
            'levels' => Yii::t('form', 'Levels'),
            'clone' => Yii::t('form', 'Clone'),
            'clone_cycle' => Yii::t('form', 'Clone Cycle'),
            'pay' => Yii::t('form', 'Pay'),
            'admin_pay_off' => Yii::t('form', 'Admin Pay Off'),
            'pay_off' => Yii::t('form', 'Pay Off'),
            'clone_pay' => Yii::t('form', 'Clone Pay'),
            'clone_admin_pay_off' => Yii::t('form', 'Clone Admin Pay Off'),
            'clone_pay_off' => Yii::t('form', 'Clone Pay Off'),
            'close_matrix_balls' => Yii::t('form', 'Close Matrix Balls'),
            'referral_matrix_balls' => Yii::t('form', 'Referral Matrix Balls'),
            'account_type' => Yii::t('form', 'Account Type'),
            'close_matrix' => Yii::t('form', 'Close Matrix'),
        ];
    }
}
