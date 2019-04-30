<?php
namespace common\modules\tickets\models\forms;

use Yii;
use yii\base\Model;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;

/**
 * Message form
 */
class MessageForm extends Model
{
	public $message;
	public $reCaptcha;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['message'], 'required'],
			[['message'], 'string'],
			[['reCaptcha'], \common\widgets\captcha\ReCaptchaValidator::className(), 'secret' => '6LeiwJ8UAAAAAHqZOLs1OC3qA4Y0HHap1YDIgDwT']
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'message' => Yii::t('form', 'Сообщение')
        ];
    }
    
    public function sendMessage($id, $type = 1, $userID = 0)
    {
		$result = false;
		
		if(!$this->validate()) 
        {	
			return false;
        }
        
        $ticketsModel = Tickets::findOne($id);
		
        if($ticketsModel !== null)
        {	
			$model = new TicketsMessages();
			$model->ticket_id = $id;
			$model->user_id = ($userID > 0) ? $userID : 1;
			$model->type = $type;
			$model->text = $this->message;
			$model->created_at = time();
			
			if($model->save(false))
			{	
				$ticketsModel->status = ($type > 1) ? 1 : 0;
					
				if($ticketsModel->save(false))
				{	
					$result = true;
				}
			}
		}
        
        return $result;
	}
}
