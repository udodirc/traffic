<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Service;
use common\models\Menu;
use common\components\Curl;
use common\models\Settings;
use common\modules\backoffice\models\Partners;
use common\models\StaticContent;
use backend\models\Content;
use common\modules\advertisement\models\TextAdvert;
use common\modules\seo\models\Seo;
use common\modules\news\models\News;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\modules\structure\models\RegisterStats;
use common\modules\slider\models\Slider;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\FeedbackForm;
use frontend\models\ContactForm;
use frontend\models\RestorePasswordForm;
use frontend\models\RestorePassword;
use common\modules\backoffice\models\forms\SignupForm;
use common\modules\backoffice\models\forms\LoginForm;
use common\modules\backoffice\models\forms\RestorePasswordEmailForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
	public $theme = '';
	
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
    
    public function beforeAction($event)
    {
		//Set theme
		$this->theme = (isset(\Yii::$app->params['defaultTheme'])) ? ('_'.\Yii::$app->params['defaultTheme']) : '';
		
        return parent::beforeAction($event);
	}
    
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {	
        if((!\Yii::$app->user->isGuest))
        {	
			$this->layout = 'backoffice'.$this->theme;
			$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
			$partnerData = Partners::findOne(['id' => $id]);
			$this->view->params['title'] = Yii::t('form', 'Новости');
			$this->view->params['tickets_list'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->all();
			$ticketsModel = new TicketsMessages();
			$this->view->params['tickets_count'] = $ticketsModel->getMessagesCountByPartnerID($id);
			$this->view->params['tickets_mesages_count'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->count();
		}
		else
		{
			$this->layout = 'main';
			$this->view->params['signupModel'] = new SignupForm();
			$this->view->params['feedbackModel'] = new FeedbackForm();
			$this->view->params['loginModel'] = new LoginForm();
			$this->view->params['restorePasswordEmailModel'] = new RestorePasswordEmailForm;
			$this->view->params['sponsorData'] = SignupForm::getSponsorData();
			$this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
			$this->view->params['curr_day_register'] = (!is_null(RegisterStats::getRegisterCountByCurrentDay())) ? RegisterStats::getRegisterCountByCurrentDay()->register_stats : 0; 
			$this->view->params['total_register'] = (!is_null(Partners::find())) ? Partners::find()->count() : 0;
			/*$this->view->params['phone'] = (!is_null(StaticContent::find()->where(['name'=>'phone'])->one())) ? StaticContent::find()->where(['name'=>'phone'])->one()->content : '';
			$this->view->params['location'] = (!is_null(StaticContent::find()->where(['name'=>'location'])->one())) ? StaticContent::find()->where(['name'=>'location'])->one()->content : '';*/
			$this->view->params['solution'] = (!is_null(StaticContent::find()->where(['name'=>'solution'])->one())) ? StaticContent::find()->where(['name'=>'solution'])->one()->content : '';
			
			$seoData = Settings::find()->where(['name'=>'seo'])->asArray()->all();
			$seoData = (!empty($seoData)) ? ArrayHelper::map($seoData, 'index', 'value') : [];
			
			$this->view->params['meta_title'] = (isset($seoData['title'])) ? $seoData['title'] : '';
			$this->view->params['meta_tags'] = (isset($seoData['meta_tags'])) ? $seoData['meta_tags'] : '';
			$this->view->params['meta_keywords'] = (isset($seoData['meta_keywords'])) ? $seoData['meta_keywords'] : '';
		}
		
        $staticContent = (!is_null(StaticContent::find()->where(['name'=>(!\Yii::$app->user->isGuest) ? 'back_office' : 'index'])->one())) ? StaticContent::find()->where(['name'=>(!\Yii::$app->user->isGuest) ? 'back_office' : 'index'])->one()->content : '' ;
        $seo = Seo::find()->where(['name'=>'index'])->one();
        
        if(!\Yii::$app->user->isGuest)
        {
			$newsList = new ActiveDataProvider([
				'query' => News::find()->where('status > 0')->orderBy('created_at DESC'),
				'pagination' => [
					'pageSize' => Service::getPageSize(),
				],
			]);

			return $this->render('backoffice_index'.$this->theme, [
				'staticContent' => $staticContent,
				'newsList' => $newsList,
				'seo' => $seo
			]);
		}
        else
        {
			$counterContent = (!is_null(StaticContent::find()->where(['name'=>'counter_content'])->one())) ? StaticContent::find()->where(['name'=>'counter_content'])->one()->content : '';
			$prelaunch = (!is_null(StaticContent::find()->where(['name'=>'prelaunch'])->one())) ? StaticContent::find()->where(['name'=>'prelaunch'])->one()->content : '';
			$partnersGeoDataList = Partners::getRandGeoDataList(100);
			$sliderList = Slider::find()->all();
			$contentList = Content::getContentList();

			return $this->render('index', [
				'counterContent' => $counterContent,
				'staticContent' => $staticContent,
				'prelaunch' => $prelaunch,
				'contentList' => $contentList,
				'partnersGeoDataList' => $partnersGeoDataList,
				'sliderList' => $sliderList,
				'seo' => $seo
			]);
		}
    }
    
    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContacts()
    {
	    if (!Yii::$app->user->isGuest)
	    {
		    return $this->goHome();
	    }

		$model = new FeedbackForm();

		$this->view->params['model'] = new LoginForm();
        $this->view->params['feedbackModel'] = $model;
        $this->view->params['brand_slogan'] = (isset(Yii::$app->params['brand_slogan'])) ? Yii::$app->params['brand_slogan'] : '';
		$this->view->params['phone'] = (!is_null(StaticContent::find()->where(['name'=>'phone'])->one())) ? StaticContent::find()->where(['name'=>'phone'])->one()->content : '';
		$this->view->params['location'] = (!is_null(StaticContent::find()->where(['name'=>'location'])->one())) ? StaticContent::find()->where(['name'=>'location'])->one()->content : '';
		$url = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
		$this->view->params['bread_crumbs'] = Menu::createBreadCrumbs(null, $url, Yii::t('form', 'Контакты'));
		$address = StaticContent::find()->where(['name'=>'address'])->one();
       
		return $this->render('contacts', [
			'model' => $model,
			'address' => $address
		]);
	}
	
	/*public function actionContacts()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$request = \Yii::$app->getRequest();
		
		//Initial vars
		$errors = [];
		$msg = Yii::t('messages', 'Ошибка!');
		$class = 'error';
		$result = false;
		
		//Check request is ajax
		if($request->isAjax) 
		{	
			//Get post
			$post = $request->post();
			$model = new FeedbackForm();
			
			if($model->load(Yii::$app->request->post()) && $model->validate())
			{	
				if($model->sendMessage($model))
				{	
					$result = true;
					$class = 'success';
					$msg = Yii::t('messages', 'Ваше сообщение отправлено!');
				}
			}
			else
			{	
				
				//Get validate errors
				$errors = [0, ActiveForm::validate($model), 'contact-form'];
			}
		}
		
		return ['result' => $result, 'errors' => $errors, 'msg' => $msg];
	}*/

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    public function actionSendFeedback()
    {	
		$model = new FeedbackForm();
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
			
		if($model->load(Yii::$app->request->post())) 
		{	
			if($model->sendMessage($model))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Ваше сообщение отправлено!');
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, $msg);
		
		return $this->redirect(['contacts']);;
    }
}
