<?php

namespace common\modules\tickets\models;

use Yii;
use common\models\DbBase;
use common\modules\backoffice\models\Partners;

/**
 * This is the model class for table "tickets".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $subject
 * @property integer $status
 * @property integer $created_at
 *
 * @property Partners $partner
 * @property TicketsMessages[] $ticketsMessages
 */
class Tickets extends \yii\db\ActiveRecord
{
	public $text;
	public $login;
	public $reCaptcha;
	
	const STATUS_PARTNER_ANSWER = 0;
    const STATUS_ADMIN_ANSWER = 1;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'text'], 'required'],
            [['partner_id', 'status', 'created_at'], 'integer'],
            [['subject', 'login'], 'string', 'max' => 100],
            [['text'], 'string'],
            [['subject', 'text'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
	        [['reCaptcha'], \common\widgets\captcha\ReCaptchaValidator::className(), 'secret' => Yii::$app->params['captcha_secret']]
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
            'partner_id' => Yii::t('form', 'ID партнера'),
            'subject' => Yii::t('form', 'Тема'),
            'text' => Yii::t('form', 'Текст сообщения'),
            'date_from' => Yii::t('form', 'Дата от:'),
            'date_to' => Yii::t('form', 'Дата до:'),
            'status' => Yii::t('form', 'Статус'),
            'created_at' => Yii::t('form', 'Создан'),
        ];
    }

	/**
	 * Scenarios
	 *
	 * @return mixed
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();

		$scenarios['with_captcha'] = ['subject', 'text', 'reCaptcha'];

		$scenarios['without_captcha'] = ['subject', 'text'];

		return $scenarios;
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
    public function getTicketsMessages()
    {
        return $this->hasMany(TicketsMessages::className(), ['ticket_id' => 'id']);
    }
    
    public function createTicket($id = 0)
    {
		$result = false;
		
        if(!$this->validate())
        {	
			return $result;
        }
        
        $adminID = (isset(\Yii::$app->params['ticket_admin'])) ? \Yii::$app->params['ticket_admin'] : 0;
        $type = 1;
        $status = 0;
        
        if($id == 0 && $this->login != '')
        {
			$partnerdData = Partners::find()->select('id')->where(['login'=>$this->login]);
			$id = ($partnerdData !== null) ? $partnerdData->one()->id : 0;
			$type = 2;
			$status = 1;
		}
        
        if($adminID > 0 && $id > 0 && $this->subject != '' && $this->text != '')
        {
			$dbModel = new DbBase();
			$procedureInData = [$id, $adminID, $this->subject, $type, $this->text, $status, '@p5'];
			$procedureOutData = ['@p5'=>'VAR_OUT_RESULT'];
			$procedureResult = $dbModel->callProcedure('create_ticket', $procedureInData, $procedureOutData);
		
			if(!empty($procedureResult))
			{
				$result = ((isset($procedureResult['output']['VAR_OUT_RESULT'])) && $procedureResult['output']['VAR_OUT_RESULT'] > 0) ? true : false;
			}
		}
        
        return $result;
	}
	
	public function createTicketWithoutForm($adminID, $id, $subject, $text)
    {
		$result = false;
		$type = 1;
        $status = 0;
        
        if($adminID > 0 && $id > 0 && $subject != '' && $text != '')
        {
			$dbModel = new DbBase();
			$procedureInData = [$id, $adminID, $subject, $type, $text, $status, '@p5'];
			$procedureOutData = ['@p5'=>'VAR_OUT_RESULT'];
			$procedureResult = $dbModel->callProcedure('create_ticket', $procedureInData, $procedureOutData);
		
			if(!empty($procedureResult))
			{
				$result = ((isset($procedureResult['output']['VAR_OUT_RESULT'])) && $procedureResult['output']['VAR_OUT_RESULT'] > 0) ? true : false;
			}
		}
        
        return $result;
	}
	
	public function getTicketsList($status)
    {
		return self::find()
		->select('`partners`.`login`, `tickets`.`id`, `tickets`.`subject`, `tickets`.`status`, `tickets`.`created_at`')
		->from('tickets')
		->leftJoin('partners', 'partners.id = tickets.partner_id')
		->where(['`tickets`.`status`'=>$status])
		->orderBy('`tickets`.`created_at` DESC');
	}
}
