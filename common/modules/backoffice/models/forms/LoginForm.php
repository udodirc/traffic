<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;
    private $_user = false;
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // login and password are both required
            [['login', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            //[['reCaptcha'], \common\widgets\captcha\ReCaptchaValidator::className(), 'secret' => '6Le3szsUAAAAAGouzZl8qnqtVt8knTS41IDJNpy1']
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'login' => Yii::t('form', 'Логин'),
            'password' => Yii::t('form', 'Пароль'),
            'reCaptcha' => Yii::t('form', 'Введите проверочный код'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            $user = $this->getUser();
            
            if(!$user || !$user->validatePassword($this->password)) 
            {	
                $this->addError($attribute, Yii::t('form', 'Неправильный пароль или логин'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
			
			$model = new Partners();
			$model->SetGeoData($this->getUser()->id);
			$model->setTopLeader($this->getUser()->id);
			\Yii::$app->session->set('user.sponsor_advert', 1);
			
			//$model->setMainModalWindow();
			
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) 
        {
            $this->_user = Partners::findByUsername($this->login);
        }
		
        return $this->_user;
    }
}
