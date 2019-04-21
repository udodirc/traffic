<?php

namespace common\modules\mailing\models;

use Yii;

/**
 * This is the model class for table "mailing_base".
 *
 * @property integer $id
 * @property integer $sponsor_id
 * @property integer $left_key
 * @property integer $right_key
 * @property integer $level
 * @property string $login
 * @property string $first_name
 * @property string $last_name
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $matrix
 * @property integer $demo_matrix
 * @property string $total_amount
 * @property string $demo_total_amount
 * @property integer $total_balls
 * @property integer $demo_total_balls
 * @property integer $group_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class MailingBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailing_base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sponsor_id', 'left_key', 'right_key', 'level', 'login', 'first_name', 'last_name', 'auth_key', 'password_hash', 'email', 'status', 'matrix', 'demo_matrix', 'total_amount', 'demo_total_amount', 'total_balls', 'demo_total_balls', 'group_id', 'created_at', 'updated_at'], 'required'],
            [['sponsor_id', 'left_key', 'right_key', 'level', 'status', 'matrix', 'demo_matrix', 'total_balls', 'demo_total_balls', 'group_id', 'created_at', 'updated_at'], 'integer'],
            [['total_amount', 'demo_total_amount'], 'number'],
            [['login', 'first_name', 'last_name'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'sponsor_id' => Yii::t('form', 'Sponsor ID'),
            'left_key' => Yii::t('form', 'Left Key'),
            'right_key' => Yii::t('form', 'Right Key'),
            'level' => Yii::t('form', 'Level'),
            'login' => Yii::t('form', 'Login'),
            'first_name' => Yii::t('form', 'First Name'),
            'last_name' => Yii::t('form', 'Last Name'),
            'auth_key' => Yii::t('form', 'Auth Key'),
            'password_hash' => Yii::t('form', 'Password Hash'),
            'password_reset_token' => Yii::t('form', 'Password Reset Token'),
            'email' => Yii::t('form', 'Email'),
            'status' => Yii::t('form', 'Status'),
            'matrix' => Yii::t('form', 'Matrix'),
            'demo_matrix' => Yii::t('form', 'Demo Matrix'),
            'total_amount' => Yii::t('form', 'Total Amount'),
            'demo_total_amount' => Yii::t('form', 'Demo Total Amount'),
            'total_balls' => Yii::t('form', 'Total Balls'),
            'demo_total_balls' => Yii::t('form', 'Demo Total Balls'),
            'group_id' => Yii::t('form', 'Group ID'),
            'created_at' => Yii::t('form', 'Created At'),
            'updated_at' => Yii::t('form', 'Updated At'),
        ];
    }
}
