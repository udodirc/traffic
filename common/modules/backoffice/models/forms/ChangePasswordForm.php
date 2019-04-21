<?php
namespace common\modules\backoffice\models\forms;

use yii\base\Model;
use Yii;
use common\modules\backoffice\models\Partners;

class ChangePasswordForm extends Model
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
            [['password', 're_password'], 'match', 'pattern' => '/^[a-zA-Z0-9]*$/u', 'message' => Yii::t('messages', 'Введенны неправильные символы!')],
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
    
     /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function changePassword($id, $model)
    {
        if(!$this->validate())
        {	
			return false;
        }
		
		$result = false;
		
		if(($partnersModel = Partners::findOne($id)) !== null) 
        {
            $partnersModel->password_hash = Yii::$app->security->generatePasswordHash($model->password); 
            $result = $partnersModel->save(false);
        }
		
		return $result;
	}
}
