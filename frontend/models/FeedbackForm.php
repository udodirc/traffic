<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Add feedback form
 */
class FeedbackForm extends Model
{
	public $name;
    public $email;
    public $text;
    public $reCaptcha;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['email'], 'email'],
            ['email', 'checkBlackList'],
            [['name', 'text'], 'string'],
            [['reCaptcha'], \common\widgets\captcha\ReCaptchaValidator::className(), 'secret' => '6LeiwJ8UAAAAAHqZOLs1OC3qA4Y0HHap1YDIgDwT']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('form', 'Имя'),
            'email' => Yii::t('form', 'Email'),
            'text' => Yii::t('form', 'Сообщение'),
        ];
    }
    
    public function checkBlackList($attribute, $param)
    {	
		if(isset(Yii::$app->params['email_black_list']))
		{
			$emailList = array_flip(Yii::$app->params['email_black_list']);
			
			if(isset($emailList[$this->email]))
			{	
				$this->addError($attribute, Yii::t('form', 'Ошибка!'));
			}
		}
	}
    
    /**
    * Send message to base.
     *
     * @return boolean
    */
    public function sendMessage($model)
    {	
        /*if(!$this->validate())
        {	
			return false;
        }*/
		
		$mailResult = \Yii::$app->mailer->compose(['html' => 'feedback-html-ru'], ['subject' => $model->text])
		->setFrom([$model->email => 'Обратная связь - '.$model->name])
		->setTo(\Yii::$app->params['supportEmail'])
		->setSubject('Тема: Обратная связь')
		->send();
		
		if($mailResult)
		{
			return true;
		}
        
       return false;
	}
}
