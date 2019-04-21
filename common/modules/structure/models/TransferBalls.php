<?php

namespace common\modules\structure\models;

use Yii;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "transfer_balls".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property integer $balls
 * @property integer $created_at
 *
 * @property Partners $receiver
 * @property Partners $sender
 */
class TransferBalls extends \yii\db\ActiveRecord
{
	public $sender_login;
	public $receiver_login;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfer_balls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'balls', 'created_at'], 'required'],
            [['sender_id', 'receiver_id', 'balls', 'created_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'sender_id' => Yii::t('form', 'Sender ID'),
            'receiver_id' => Yii::t('form', 'Receiver ID'),
            'balls' => Yii::t('form', 'Баллы'),
            'sender_login' => Yii::t('form', 'Отправитель'),
            'receiver_login' => Yii::t('form', 'Получатель'),
            'created_at' => Yii::t('form', 'Дата передачи'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(Partners::className(), ['id' => 'receiver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(Partners::className(), ['id' => 'sender_id']);
    }
}
