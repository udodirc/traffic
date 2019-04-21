<?php
namespace common\modules\tickets\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;
use common\modules\tickets\models\Tickets;

/**
 * MailingForm model
 */
class MailingForm extends Model
{
	public $country;
	public $status;
	public $top_leaders_count;
	public $partners_offset;
	public $partners_count;
	public $subject;
	public $message;
    public $tickets;
    public $email;
	
	 /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['message', 'subject'], 'required'],
			[['country'], 'string'],
			[['subject'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
			[['top_leaders_count', 'status', 'partners_offset', 'partners_count', 'tickets', 'email'], 'integer'],
		];
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country' => Yii::t('form', 'Страна'),
            'status' => Yii::t('form', 'Статус'),
            'top_leaders_count' => Yii::t('form', 'Кол-во рефералов'),
            'partners_offset' => Yii::t('form', 'Отступ партнеров'),
			'partners_count' => Yii::t('form', 'Кол-во партнеров'),
			'subject' => Yii::t('form', 'Тема'),
            'message' => Yii::t('form', 'Сообщение'),
            'tickets' => Yii::t('form', 'Тикеты'),
            'email' => Yii::t('form', 'Email')
        ];
    }
	
	public function checkActions($attribute, $param)
    {
		if($this->tickets == 0 && $this->email == 0) 
		{
			$this->addError($attribute, Yii::t('form', 'Это поле должно быть заполнено!'));
		}
	}
	
	/**
     * Reserve places in matrix.
     *
     * @return boolean
    */
     public function send()
    {
		$result = false;
		
        if(!$this->validate())
        {	
			return $result;
        }
        
        $model = new Partners();
        $partnersList = $model->getPartnerListByEmailFilters($this->country, $this->status, $this->top_leaders_count, $this->partners_count, $this->partners_offset);
        
        if(!empty($partnersList) && $this->tickets > 0)
        {
			$ticketsModel = new Tickets();
			$adminID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
        
			if($this->createTickets($adminID, $ticketsModel, $partnersList))
			{
				$result = true;
			}
		}
		
		if(!empty($partnersList) && $this->email > 0)
        {
			if($this->sendEmail($partnersList))
			{
				$result = true;
			}
			else
			{
				$result = false;
			}
		}
        
        return $result;
	}
	
	public function createTickets($adminID, $model, $partnersList)
    {
		$result = true;
		
		foreach($partnersList as $i => $partnerData)
		{	
			if(!$model->createTicketWithoutForm($adminID, $partnerData['id'], $this->subject, $this->message))
			{
				$result = false;
				break;
			}
		}
		
		return $result;
	}
	
	public function sendEmail($partnersList)
    {
		$result = false;
		
		if(isset(\Yii::$app->params['supportEmail']))
		{
			foreach($partnersList as $i => $partnerData)
			{
				$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
				$result = \Yii::$app->mailer->compose(['html' => 'message-html-ru'], ['subject' => $this->subject, 'message' => $this->message, 'site' => Url::base(true)])
				->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
				->setTo($partnerData['email'])
				->setSubject($this->subject)
				->send();
			}
		}
		
		return $result;
	}
}
