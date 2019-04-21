<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ReservationForm is the model behind the reservation form.
 */
class ReservationForm extends Model
{
    public $first_name;
    public $last_name;
    public $phone;
    public $email;
    public $body;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['first_name', 'phone'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           'first_name' => 'Имя',
           'last_name' => 'Фамилия',
           'phone' => 'Телефон',
           'email' => 'E-mail',
           'body' => 'Комментарий к брони',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom($this->email)
            ->setSubject('Test Mail')
            ->setTextBody($this->body)
            ->send();
    }
}
