<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['email', 'checkBlackList'],
            // verifyCode needs to be entered correctly
            //['verifyCode', 'captcha'],
            [['reCaptcha'], \common\widgets\captcha\ReCaptchaValidator::className(), 'secret' => '6LdaboEUAAAAAOAJh_jVRcLMs1wsW6VOQR_9CObO']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reCaptcha' => Yii::t('form', 'Введите проверочный код'),
        ];
    }
    
    public function checkBlackList($attribute, $param)
    {	
		if(isset(Yii::$app->params['email_black_list']))
		{
			if(isset(Yii::$app->params['email_black_list'][$this->email]))
			{
				$this->addError($attribute, Yii::t('form', 'Ошибка!'));
			}
		}
	}

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        /*return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();*/
          return true;  
    }
}
