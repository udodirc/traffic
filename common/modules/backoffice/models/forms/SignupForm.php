<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;
use common\modules\backoffice\models\Payments;
use common\modules\structure\models\Matrix;
use common\models\DbBase;

/**
 * Signup form
 */
class SignupForm extends Model
{
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 0;
	
	public $id;
	public $sponsor_id;
	public $sponsor_login;
    public $login;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $auth_key;
    public $rules_agree;
    public $password;
    public $re_password;
    public $verifyCode;
	public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sponsor_login', 'login'], 'filter', 'filter' => 'trim'],
            [['login', 'first_name', 'last_name', 'email', 'password', 're_password'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            //['rules_agree', 'required', 'requiredValue' => 1, 'message' => Yii::t('form', 'Вы должны быть согласны с правилами сайта!')],
            ['login', 'unique', 'targetClass' => '\common\modules\backoffice\models\Partners', 'message' => 'Этот пользователь уже зарегистрирован!'],
            [['first_name', 'last_name'], 'string', 'min' => 2, 'max' => 100],
            [['sponsor_login', 'login'], 'string', 'min' => 2, 'max' => 30],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 50],
            ['email', 'unique', 'targetClass' => '\common\modules\backoffice\models\Partners', 'message' => 'Этот email уже зарегистрирован.'],
            [['rules_agree'], 'integer'],
            //[['phone'], 'checkPhone', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['sponsor_login', 'login', 'password', 're_password'], 'match', 'pattern' => '/^[a-zA-Z0-9]*$/u', 'message' => Yii::t('form', 'Введенны неправильные символы!')],
            ['re_password', 'compare', 'compareAttribute' => 'password'],
            //['verifyCode', 'captcha'],
            [['reCaptcha'], \common\widgets\captcha\ReCaptchaValidator::className(), 'secret' => '6LeiwJ8UAAAAAHqZOLs1OC3qA4Y0HHap1YDIgDwT']
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'sponsor_login' => Yii::t('form', 'Логин спонсора'),
            'login' => Yii::t('form', 'Логин'),
            'first_name' => Yii::t('form', 'Имя'),
            'last_name' => Yii::t('form', 'Фамилия'),
            'password' => Yii::t('form', 'Пароль'),
            're_password' => Yii::t('form', 'Повторить пароль'),
            'email' => Yii::t('form', 'Email'),
            'phone' => Yii::t('form', 'Телефон'),
            'rules_agree' => Yii::t('form', 'С правилами сайта согласен'),
            'reCaptcha' => Yii::t('form', 'Введите проверочный код'),
            //'verifyCode' => Yii::t('form', 'captcha'),
        ];
    }
    
    public function checkPhone($attribute, $param)
    {	
		if(!(bool)preg_match('^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$', trim($this->phone)))
		{	
			$this->addError($attribute, Yii::t('form', 'Введите правильный номер телефона!'));
		}
	}
    
    /**
     * Scenarios
     *
     * @return mixed
     */
    public function scenarios()
	{
		$scenarios = parent::scenarios();
		
        $scenarios['front_register'] = ['login', 'first_name', 'last_name', 'email', 'password', 're_password'];
		
		$scenarios['backend_register'] = ['sponsor_login', 'login', 'first_name', 'last_name', 'email', 'password', 're_password'];
        
		return $scenarios;
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
		$result = ['result' => false, 'model' => []];
		
        if(!$this->validate())
        {	
			return $result;
        }
		
		$structureNumber = 1;
		$partnerID = 0;
		$sponsorID = ($this->sponsor_id > 1) ? $this->sponsor_id : 1;
		$model = new Partners();
        $model->sponsor_id = $this->sponsor_id;
        $model->login = mb_strtolower($this->login, 'UTF-8');
        $model->first_name = $this->first_name;
        $model->last_name = $this->last_name;
        $model->email = $this->email;
        $model->phone = $this->phone;
        $model->group_id = 0;
        $model->status = self::STATUS_ACTIVE;
        $model->created_at = 0;
        $model->setPassword($this->password);
        $model->generateAuthKey();
        
        if($this->sponsor_login != '')
        {	
			$sponsorID = (!is_null(Partners::find()->select('id')->where('login = :login', [':login'=>$this->sponsor_login])->one())) ? Partners::find()->select('id')->where('login = :login', [':login'=>$this->sponsor_login])->one()->id : $sponsorID;
		}
		
		$dbModel = new DbBase();
		$demoActivation = (isset(\Yii::$app->params['demo_structure_activation']) && (\Yii::$app->params['demo_structure_activation'])) ? 1 : 0;
		$procedureInData = [$structureNumber, $sponsorID, $model->login, $model->first_name, $model->last_name, $model->email, $model->phone, $model->password_hash, $model->created_at, $model->auth_key, $model->status, $demoActivation, '@p7'];
		$procedureOutData = ['@p7'=>'VAR_OUT_RESULT'];
		$procedureResult = $dbModel->callProcedure('add_partner_in_structure', $procedureInData, $procedureOutData);
		
		if(!empty($procedureResult))
		{
			$outResult = ((isset($procedureResult['output']['VAR_OUT_RESULT'])) && $procedureResult['output']['VAR_OUT_RESULT'] > 0) ? true : false;
			
			if($outResult)
			{
				$partnerID = ((isset($procedureResult['output']['VAR_OUT_RESULT'])) && $procedureResult['output']['VAR_OUT_RESULT'] > 0) ? $procedureResult['output']['VAR_OUT_RESULT'] : 0;
				$result = ['result' => $outResult, 'model' => [$partnerID, $model->first_name, $model->last_name, $model->email, $model->auth_key, $model->login, $this->password]];
			}
		}
		
		return $result;
    }
    
    public static function getSponsorLogin($sponsorData = null, $login = '')
    {
		$result = '';
		
		if($sponsorData !== null)
		{
			if(!isset(Yii::$app->request->cookies['referal'])) 
			{
				Yii::$app->response->cookies->add(new \yii\web\Cookie([
					'name' => 'referal',
					'value' => $sponsorData->login,
					'expire' => time() + 3600 * 24 * 30,
				]));
			}
			else
			{
				if(Yii::$app->request->cookies['referal'] != $login)
				{	
					Yii::$app->response->cookies->add(new \yii\web\Cookie([
						'name' => 'referal',
						'value' => $sponsorData->login,
						'expire' => time() + 3600 * 24 * 30,
					]));
				}
			}
			
			$result = Yii::$app->request->cookies['referal'];
		}
		else
		{
			if($login == '')
			{
				$result = isset(Yii::$app->request->cookies['referal']) ? Yii::$app->request->cookies['referal'] : '';
			}
		};
		
		return $result;
	}
	
	public static function getSponsorData()
    {
		$result = null;
		$login = self::getSponsorLogin();
		echo $login;
		$result = ($login != '') ? Partners::findByUsername($login) : Partners::find()->where(['id'=>1])->one();
		
		return $result;
	}
}
