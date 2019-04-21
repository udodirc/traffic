<?php
namespace common\modules\backoffice\models\forms;

use yii\base\Model;
use Yii;

class RestorePasswordForm extends Model
{
    public $password;
    public $re_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['password', 're_password'], 'required', 'message' => Yii::t('messages', 'Это поле должно быть заполнено!')],
            [['password', 're_password'], 'match', 'pattern' => '/^[A-Za-z0-9_\s,]+$/u', 'message' => Yii::t('messages', 'Введенны неправильные символы!')],
            ['re_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('form', 'Пароль'),
            're_password' => Yii::t('form', 'Повторить пароль')
        ];
    }
}
