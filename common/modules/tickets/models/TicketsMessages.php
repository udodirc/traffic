<?php

namespace common\modules\tickets\models;

use Yii;

/**
 * This is the model class for table "tickets_messages".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $ticket_id
 * @property integer $status
 * @property string $text
 * @property integer $created_at
 *
 * @property Tickets $ticket
 * @property User $user
 */
class TicketsMessages extends \yii\db\ActiveRecord
{
	const ADMIN_TYPE = 2;
	
	public $username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tickets_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ticket_id', 'status', 'text', 'created_at'], 'required'],
            [['user_id', 'ticket_id', 'status', 'created_at'], 'integer'],
            [['text'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'user_id' => Yii::t('form', 'User ID'),
            'ticket_id' => Yii::t('form', 'Ticket ID'),
            'status' => Yii::t('form', 'Status'),
            'text' => Yii::t('form', 'Text'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Tickets::className(), ['id' => 'ticket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getMessagesList($id)
    {
		return self::find()
		->select('`user`.`username`, `tickets_messages`.`id`, `tickets_messages`.`type`, `tickets_messages`.`text`, `tickets_messages`.`created_at`')
		->from('tickets')
		->leftJoin('tickets_messages', 'tickets_messages.ticket_id = tickets.id')
		->leftJoin('user', 'tickets_messages.user_id = user.id')
		->where(['tickets.id'=>$id])
		->orderBy('`tickets_messages`.`created_at`');
	}
	
	public function getMessagesCountByPartnerID($id)
    {
		$result = self::find()
		->from('tickets')
		->leftJoin('tickets_messages', 'tickets_messages.ticket_id = tickets.id')
		->where(['`tickets`.`partner_id`'=>$id, '`tickets_messages`.`type`'=>self::ADMIN_TYPE])->count();
		
		return ($result !== null) ? $result : 0;
	}
}
