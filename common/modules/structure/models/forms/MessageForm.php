<?php
namespace common\modules\structure\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;
use common\modules\tickets\models\Tickets;
use common\modules\structure\models\PaymentsFaul;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 *  Message form
 */
class MessageForm extends Model
{
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
            //[['message'], 'string', 'max' => 255, 'message' => Yii::t('form', 'В этом поле максимально допустимо 255 символов!')],
            [['subject'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
            [['tickets', 'email'], 'integer'],
            [['tickets', 'email'], 'checkActions'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
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
     * Send message.
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
        
        $model = new PaymentsFaul();
        $paymentsFaulList = $model->getFaulsWithPartnersList();
        $paymentsFaulList = ArrayHelper::map($paymentsFaulList, 'partner_id', 'email');
        
        if($paymentsFaulList != null && $this->tickets > 0)
        {
			$ticketsModel = new Tickets();
			$adminID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
        
			if($this->createTickets($adminID, $ticketsModel, $paymentsFaulList))
			{
				$result = true;
			}
		}
		
		if($paymentsFaulList != null && $this->email > 0)
        {
			if($this->sendEmail($paymentsFaulList))
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
	
	public function createTickets($adminID, $model, $paymentsFaulList)
    {
		$result = true;
		
		foreach($paymentsFaulList as $partnerID => $paymentData)
		{	
			if(!$model->createTicketWithoutForm($adminID, $partnerID, $this->subject, $this->message))
			{
				$result = false;
				break;
			}
		}
		
		return $result;
	}
	
	public function sendEmail($paymentsFaulList)
    {
		$result = false;
		
		if(isset(\Yii::$app->params['supportEmail']))
		{
			foreach($paymentsFaulList as $partnerID=>$email)
			{
				$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
				$result = \Yii::$app->mailer->compose(['html' => 'message-html-ru'], ['subject' => $this->subject, 'message' => $this->message, 'site' => Url::base(true)])
				->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
				->setTo($email)
				->setSubject($this->subject)
				->send();
			}
		}
		
		return $result;
	}
}
