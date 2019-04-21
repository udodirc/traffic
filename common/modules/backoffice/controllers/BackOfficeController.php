<?php
namespace common\modules\backoffice\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\backoffice\models\forms\LoginForm;
use frontend\models\FeedbackForm;
use common\modules\backoffice\models\Partners;
use common\models\StaticContent;
use common\models\Menu;
use common\modules\backoffice\models\forms\SignupForm;
use common\modules\backoffice\models\forms\RestorePasswordEmailForm;
use common\modules\backoffice\models\forms\RestorePasswordForm;
use common\modules\backoffice\models\forms\RestorePassword;

/**
 * BackOfficeController
 */
class BackOfficeController extends Controller
{
	public $layout = 'back_office';
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays back office.
     *
     * @return mixed
     */
    public function actionIndex()
    {	
		$this->layout = (!\Yii::$app->user->isGuest) ? 'back_office' : 'main';
		$this->view->params['model'] = new LoginForm();
		$this->view->params['feedbackModel'] = new FeedbackForm();
		$this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$staticContent = StaticContent::find()->where(['name'=>(!\Yii::$app->user->isGuest) ? 'back_office' : 'index'])->one();
		
        return $this->render('index', [
			'staticContent' => $staticContent,
		]);
    }
    
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    /*public function actionLogin()
    {
		$this->layout = 'frontend';
		$model = new LoginForm();
		$this->view->params['model'] = $model;
		$this->view->params['feedbackModel'] = new FeedbackForm();
		$this->view->params['contacts'] = (!is_null(StaticContent::find()->where(['name'=>'contacts']))) ? StaticContent::find()->where(['name'=>'contacts'])->one() : '';
		$this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
		$this->view->params['phone'] = (!is_null(StaticContent::find()->where(['name'=>'phone'])->one())) ? StaticContent::find()->where(['name'=>'phone'])->one()->content : '';
		$this->view->params['location'] = (!is_null(StaticContent::find()->where(['name'=>'location'])->one())) ? StaticContent::find()->where(['name'=>'location'])->one()->content : '';
		$url = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
		$this->view->params['bread_crumbs'] = Menu::createBreadCrumbs(null, $url, Yii::t('form', 'Вход'));
			
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }
        
        if($model->load(Yii::$app->request->post()) && $model->login()) 
        {
			return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/structure');
        } 
        else 
        {
			return $this->render('login', [
                'model' => $model,
            ]);
        }
    }*/
    
    
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$errors = [];
		$msg = Yii::t('messages', 'Failure!');
		$class = 'error';
		$url = '';
		$result = false;
		
		//Check request is ajax
		if($request->isAjax) 
		{	
			$model = new LoginForm();
				
			if(!Yii::$app->user->isGuest) 
			{	
				return $this->goHome();
			}
			
			if($model->load(Yii::$app->request->post()) && $model->login())
			{	
				$result = true;
				$class = 'success';
				$msg = Yii::t('messages', 'You are loggin!');
				$url = \Yii::$app->request->BaseUrl.'/partners/structure';
			}
			else
			{
				//Get validate errors
				$errors = [0, ActiveForm::validate($model), 'login-form'];
			}
		}
		
		return ['result' => $result, 'errors' => $errors, 'msg' => $msg, 'url' => $url];
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	/**
     * Signs user up.
     *
     * @return mixed
     */
    /*public function actionSignup()
    {
		$this->layout = (!\Yii::$app->user->isGuest) ? 'back_office' : 'main';
		$this->view->params['model'] = new LoginForm();
		$this->view->params['feedbackModel'] = new FeedbackForm();
		$this->view->params['contacts'] = (!is_null(StaticContent::find()->where(['name'=>'contacts']))) ? StaticContent::find()->where(['name'=>'contacts'])->one() : '';
		$this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
		$this->view->params['phone'] = (!is_null(StaticContent::find()->where(['name'=>'phone'])->one())) ? StaticContent::find()->where(['name'=>'phone'])->one()->content : '';
		$this->view->params['location'] = (!is_null(StaticContent::find()->where(['name'=>'location'])->one())) ? StaticContent::find()->where(['name'=>'location'])->one()->content : '';
		$url = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
		$this->view->params['bread_crumbs'] = Menu::createBreadCrumbs(null, $url, Yii::t('form', Yii::t('menu', 'Регистрация')));
				
		$post = Yii::$app->request->post('SignupForm');
		$model = new SignupForm();
		$sposnorLogin = SignupForm::getSponsorLogin();
        $sponsorData = ($sposnorLogin != '') ? Partners::findByUsername($sposnorLogin) : Partners::find()->where(['id'=>1])->one();
        $model->sponsor_id = (isset($post['sponsor_id'])) ? intval($post['sponsor_id']) : 0;
        $model->sponsor_login = (isset($post['sponsor_login'])) ? $post['sponsor_login'] : '';
		
        if($model->load(Yii::$app->request->post()) && $sponsorData != null) 
        {	
			$mailResult = false;
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			$result = $model->signup();
			
			if($result['result']) 
            {	
				$mailResult = true;
				
				if(isset(\Yii::$app->params['supportEmail']))
				{
					$url = Url::base(true);
						
					if(!strpos($url, 'localhost'))
					{	
						$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
						$mailResult = \Yii::$app->mailer->compose(['html' => 'signup-html'], ['partner_id' => $result['model'][0], 'first_name' => $result['model'][1], 'last_name' => $result['model'][2], 'email' => $result['model'][3], 'auth_key' => $result['model'][4], 'login' => $result['model'][5], 'site' => Url::base(true)])
						->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
						->setTo($model->email)
						->setSubject('► '.Yii::t('messages', 'Подтверждение регистрации'))
						->send();
						
						if($mailResult)
						{
							$mailResult = false;
							$mailResult = \Yii::$app->mailer->compose(['html' => 'new-refferal-html'], ['first_name' => $sponsorData->first_name, 'last_name' => $sponsorData->last_name, 'refferal_name' => $result['model'][5], 'site' => Url::base(true)])
							->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
							->setTo($sponsorData->email)
							->setSubject('► '.Yii::t('messages', 'Новый Реферал!'))
							->send();
						}
					}
				}
				
				if($mailResult)
				{
					$class = 'success';
					$msg = Yii::t('messages', 'Вы зарегистрированы! Подверждение регистрации отправлено вам на почту.');
					$content = (!is_null(StaticContent::find()->where(['name'=>'success_signup']))) ? StaticContent::find()->where(['name'=>'success_signup'])->one() : '';
					\Yii::$app->getSession()->setFlash($class, $msg);
							
					return $this->render('success_signup', [
						'content' => $content,
					]);
				}
            }
            
            \Yii::$app->getSession()->setFlash($class, $msg);
        }
		
        return $this->render('signup', [
            'model' => $model,
            'sponsorData' => $sponsorData,
            'sponsorLogin' => $sposnorLogin,
        ]);
    }*/
    
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$errors = [];
		$msg = Yii::t('messages', 'Failure!');
		$class = 'error';
		$result = false;
		
		//Check request is ajax
		if($request->isAjax) 
		{
			$post = Yii::$app->request->post('SignupForm');
			$model = new SignupForm();
			$sposnorLogin = SignupForm::getSponsorLogin();
			$sponsorData = ($sposnorLogin != '') ? Partners::findByUsername($sposnorLogin) : Partners::find()->where(['id'=>1])->one();
			$model->sponsor_id = (isset($post['sponsor_id'])) ? intval($post['sponsor_id']) : 0;
			$model->sponsor_login = (isset($post['sponsor_login'])) ? $post['sponsor_login'] : '';
				
			if($model->load(Yii::$app->request->post()) && $sponsorData != null) 
			{
				$mailResult = false;
				$signupResult = $model->signup();
				
				if(isset($signupResult['result']))
				{
					if($signupResult['result']) 
					{
						$mailResult = true;
						
						if(isset(\Yii::$app->params['supportEmail']))
						{
							$url = Url::base(true);
							
							if(!strpos($url, 'localhost'))
							{	
								$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
								$mailResult = \Yii::$app->mailer->compose(['html' => 'signup-html-ru'], ['partner_id' => $signupResult['model'][0], 'first_name' =>$signupResult['model'][1], 'last_name' => $signupResult['model'][2], 'email' => $signupResult['model'][3], 'auth_key' => $signupResult['model'][4], 'login' => $signupResult['model'][5], 'site' => Url::base(true)])
								->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
								->setTo($model->email)
								->setSubject(Yii::t('messages', '► Confirm of registration'))
								->send();
								
								if($mailResult)
								{
									$mailResult = false;
									$mailResult = \Yii::$app->mailer->compose(['html' => 'new-refferal-html-ru'], ['first_name' => $sponsorData->first_name, 'last_name' => $sponsorData->last_name, 'refferal_name' => $signupResult['model'][5], 'site' => Url::base(true)])
									->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
									->setTo($sponsorData->email)
									->setSubject(Yii::t('messages', '► New refferal!'))
									->send();
								}
							}
							
							if($mailResult)
							{	
								$result = true;
								$class = 'success';
								$msg = Yii::t('messages', 'You registered! Confirm of registration is sent to your email.');
							}
						}
					}
					else
					{
						//Get validate errors
						$errors = [0, ActiveForm::validate($model), 'register-form'];
					}
				}
				else
				{	
					//Get validate errors
					$errors = [0, ActiveForm::validate($model), 'register-form'];
				}
			}
			else
			{	
				//Get validate errors
				$errors = [0, ActiveForm::validate($model), 'register-form'];
			}
		}
		
		return ['result' => $result, 'errors' => $errors,  'msg' => $msg];
    }
    
    public function actionReferal($login)
    {
		$sponsorData = Partners::findByUsername($login);
		SignupForm::getSponsorLogin($sponsorData, $login);
		
		return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionConfirmRegistration($id, $auth_key)
    {
		$result = false;
		$class = 'success';
		$model = Partners::findOne($id);
		$msg = Yii::t('messages', 'Вы уже зарегистрированы!');
		
		if($model !== null)
		{	
			if($model->status == 0)
			{
				$class = 'error';
				$msg = Yii::t('messages', 'Ошибка!');
				
				if($model->auth_key == $auth_key)
				{
					$model->status = 1;
					
					if($model->save(false))
					{	 
						$this->layout = 'main';
						$class = 'success';
						$msg = Yii::t('messages', 'Вы прошли проверку!');
						$result = true;
						
						$content = (!is_null(StaticContent::find()->where(['name'=>'success_confirm']))) ? StaticContent::find()->where(['name'=>'success_confirm'])->one() : '';
					
						return $this->render('success_signup', [
							'content' => $content,
						]);
					}
				}
			}
		}
		
		if(!$result)
		{
			\Yii::$app->getSession()->setFlash($class, $msg);
			return $this->goHome();
		}
	}
	
	public function actionRestorePassword()
    {
		$this->layout = (!\Yii::$app->user->isGuest) ? 'back_office' : 'main';
		$this->view->params['model'] = new LoginForm();
		$this->view->params['feedbackModel'] = new FeedbackForm();
		$this->view->params['contacts'] = (!is_null(StaticContent::find()->where(['name'=>'contacts']))) ? StaticContent::find()->where(['name'=>'contacts'])->one() : '';
		$formModel = new RestorePasswordEmailForm;
		
		if($formModel->load(Yii::$app->request->post())) 
		{
			$result = array(
				'class' => 'error',
				'messages' => Yii::t('messages', 'Пользователь с данным email адресом не зарегистрирован!')
			);
			$user = Partners::find()->where(['email' => $formModel->email])->one();
			
			if(empty($user)) 
			{
				$result = array(
					'class' => 'error',
					'messages' => Yii::t('messages', 'Такого пользователя нет!')
				);	
			}
			else
			{
				$hash = Yii::$app->getSecurity()->generateRandomString(32);
				$model = RestorePassword::find()->where(['user_id' => $user->id])->one();
			
				if($model === NULL)
				{	
					$model = new RestorePassword;
					$model->user_id = $user->id;
				}
				else
				{
					$datetime1 = new \DateTime($model->created_at);
					$datetime2 = new \DateTime(date('Y-m-d H:i:s'));
					$interval = $datetime1->diff($datetime2);
				
					if($interval->d == 0)
					{
						$model->hash = $hash;
						$model->created_at = date('Y-m-d H:i:s');
						$model->save(false);  // a new row is inserted into user table
					
						$result = array(
							'class' => 'success',
							'messages' => Yii::t('messages', 'Новый пароль уже выслан на ваш email!')
						);
					}
				}
				
				$model->hash = $hash;
				$model->created_at = date('Y-m-d H:i:s');
			
				// a new row is inserted into user table
				if($model->save(false))
				{ 	
					if(isset(\Yii::$app->params['supportEmail']))
					{
						$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
						$mailResult = \Yii::$app->mailer->compose(['html' => 'restorePassword-html'], ['user_id' => $user->id, 'username' => $user->first_name.'&nbsp;'.$user->last_name, 'login' => $user->login, 'email' => $user->email, 'hash' => $model->hash, 'site' => Url::base(true)])
						->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
						->setTo($user->email)
						->setSubject(Yii::t('messages', 'Тема: Восстановления пароля'))
						->send();
					
						$result = array(
							'class' => ($mailResult) ? 'success' : 'error',
							'messages' => ($mailResult) ? Yii::t('messages', 'Новый пароль выслан на ваш email!') : Yii::t('messages', 'Ошибка!'),
						);
					}
				}
			}
			
			\Yii::$app->getSession()->setFlash($result['class'], $result['messages']);
			
			return $this->redirect(['restore-password']);
		}
		
		return $this->render('restore_password_email', [
            'model' => $formModel
        ]);
	}
	
	public function actionRestorePasswordForm($id, $hash) 
	{
		$this->layout = 'main';
		$this->view->params['feedbackModel'] = new FeedbackForm();
		$this->view->params['contacts'] = (!is_null(StaticContent::find()->where(['name'=>'contacts']))) ? StaticContent::find()->where(['name'=>'contacts'])->one() : '';
		
		$id = intval($id);
		$access = false;
		$formModel = new RestorePasswordForm;
		$model = RestorePassword::find()->where(['user_id' => $id, 'hash' => $hash])->one();
		
		if($model !== null)
		{
			$datetime1 = new \DateTime($model->created_at);
			$datetime2 = new \DateTime(date('Y-m-d H:i:s'));
			$interval = $datetime1->diff($datetime2);
			$access = ($interval->d == 0) ? true : false;	
		
			if($access)
			{
				if($formModel->load(Yii::$app->request->post())) 
				{
					$hash = Yii::$app->getSecurity()->generatePasswordHash($formModel->password);
					$model = Partners::find()->where(['id' => $id])->one();
					$model->password_hash = $hash;
					$model->save(false);
				
					return $this->redirect(['login']);
				}	
			}
		}
		
		return $this->render('restore_password_form', array(
			'access'=>$access,
			'user_id'=>$id,
			'model' => $formModel
		));
	}
	
	public function actionRulesAgree() 
	{
		$this->layout = 'main';
		$content = StaticContent::find()->where(['name'=>'rules_agree'])->one();
       
		return $this->render('rules_agree', [
			'content' => $content
		]);
	}
}
