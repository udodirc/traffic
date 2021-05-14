<?php
namespace common\modules\backoffice\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\Matrix1;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\MatrixPaymentsSearch;
use common\modules\structure\models\DemoMatrixPayments;
use common\modules\structure\models\DemoMatrixPaymentsSearch;
use common\modules\structure\models\DemoInvitePayOff;
use common\modules\structure\models\DemoInvitePayOffSearch;
use common\modules\structure\models\InvitePayOff;
use common\modules\structure\models\InvitePayOffSearch;
use common\modules\structure\models\TopReferals;
use common\modules\structure\models\RegisterStats;
use common\modules\structure\models\DemoLevelsPayment;
use common\modules\structure\models\LevelsPayment;
use common\modules\structure\models\LevelsPecentage;
use common\modules\structure\models\MatricesSettings;
use common\modules\structure\models\DemoBalls;
use common\modules\structure\models\Balls;
use common\modules\structure\models\BallsSearch;
use common\modules\structure\models\GoldToken;
use common\modules\structure\models\PaymentsInvoices;
use common\modules\structure\models\Withdrawal;
use common\modules\structure\models\forms\ReserveForm;
use common\modules\structure\models\Payment;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\components\geo\Sypexgeo;
use common\components\geo\IsoHelper;
use common\components\advacash\Merchant;
use common\modules\backoffice\models\forms\RequestForm;
use common\modules\backoffice\models\forms\ChangePasswordForm;
use common\modules\backoffice\components\PartnersHelper;
use common\modules\backoffice\models\Partners;
use common\modules\backoffice\models\forms\LoginForm;
use common\modules\backoffice\models\forms\PaymentForm;
use common\modules\backoffice\models\Payments;
use common\modules\advertisement\models\SponsorAdvert;
use common\models\Service;
use common\models\StaticContent;
use frontend\models\FeedbackForm;

/**
 * FrontendPartnersController
 */
class FrontendPartnersController extends Controller
{
	public $layout = 'backoffice';
	public $theme = '';
	protected $user_id;
	protected $identity_id;
	
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    public function beforeAction($event)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$this->view->params['tickets_list'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->all();
		$ticketsModel = new TicketsMessages();
		$this->view->params['tickets_count'] = $ticketsModel->getMessagesCountByPartnerID($id);
		$this->view->params['tickets_mesages_count'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->count();
		
		//Set theme
		$this->theme = (isset(\Yii::$app->params['defaultTheme'])) ? ('_'.\Yii::$app->params['defaultTheme']) : '';
		
        return parent::beforeAction($event);
    }
    
    /**
     * Check permission's rights
     * @return mixed
    */
    public function afterAction($action, $result)
    {
		if($this->user_id > 0 && $this->identity_id > 0)
		{
			if($this->user_id != $this->identity_id) 
			{
				throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
			}
		}
		else
		{
			throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
		}
		
        return parent::afterAction($action, $result);
    }

    /**
     * Displays a single Partners model.
     * @param integer $id
     * @return mixed
     */
    public function actionProfile($id)
    {
		$this->user_id = $id;
		$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$model = new Partners;		
		$partnerInfo = $model->getPartnerInfo($id);
		
		if($partnerInfo !== null) 
        {
			$demo = ($partnerInfo['matrix_1'] > 0) ? false : true; 	
			$referalsCount = (!is_null($model::find()->where(['sponsor_id'=>$id]))) ? $model::find()->where(['sponsor_id'=>$id])->count() : 0;
			$demoInvitePayOff = DemoInvitePayOff::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['benefit_partner_id'=>$id])->groupBy('`structure_number`')->asArray()->all();
			$demoInvitePayOff = (!empty($demoInvitePayOff)) ? ArrayHelper::map($demoInvitePayOff, 'matrix_number', 'amount') : [];
			$demoMatrixPayments = DemoMatrixPayments::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['partner_id'=>$id, 'type'=>2])->groupBy('`structure_number`')->asArray()->all();
			$demoMatrixPayments = (!empty($demoMatrixPayments)) ? ArrayHelper::map($demoMatrixPayments, 'structure_number', 'amount') : [];
			$invitePayOff = InvitePayOff::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['benefit_partner_id'=>$id])->groupBy('`structure_number`')->asArray()->all();
			$invitePayOff = (!empty($invitePayOff)) ? ArrayHelper::map($invitePayOff, 'structure_number', 'amount') : [];
			$matrixPayments = MatrixPayments::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['partner_id'=>$id, 'type'=>2])->groupBy('`structure_number`')->asArray()->all();
			$matrixPayments = (!empty($matrixPayments)) ? ArrayHelper::map($matrixPayments, 'structure_number', 'amount') : [];
			$partnerEarningsInfo = Partners::getPartnerEarningsInfo($partnerInfo, $demoInvitePayOff, $demoMatrixPayments, $invitePayOff, $matrixPayments);
			$isoList = IsoHelper::getIsoList('en');
			$goldTokenSum = GoldToken::find()->where(['partner_id'=>$id])->sum('amount');
			$content = (!is_null(StaticContent::find()->where(['name'=>'profile']))) ? StaticContent::find()->where(['name'=>'profile'])->one() : null;
			$this->view->params['title'] = Yii::t('form', 'Профиль');
			$structureNumber = 1;
			
			$dataProvider = new ActiveDataProvider([
				'query' => $model->getRefferalListByPartnerID($structureNumber, $id),
				'pagination' => [
					'pageSize' => 50,
				],
			]);
			
			return $this->render('profile'.$this->theme, [
				'dataProvider' => $dataProvider,
				'model' => $partnerInfo,
				'partnerEarningsInfo' => $partnerEarningsInfo,
				'referalsCount' => $referalsCount,
				'demo' => $demo,
				'isoList' => $isoList,
				'goldTokenSum' => $goldTokenSum,
				'content' => $content,
				'statuses_list' => (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [],
			]);
		}
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
    
    /**
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->user_id = $id;
		$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$model = $this->findModel($id);
        $model->scenario = ('update');
        $model->payeer_wallet = trim($model->payeer_wallet);
        $content = (!is_null(StaticContent::find()->where(['name'=>'update_profile']))) ? StaticContent::find()->where(['name'=>'update_profile'])->one() : null;
        $this->view->params['title'] = Yii::t('form', 'Редактирование профиля');
        
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
            return $this->redirect(['profile', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('update'.$this->theme, [
                'model' => $model,
                'content' => $content
            ]);
        }
    }
    
    /**
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionChangePassword($id)
    {
		$this->user_id = $id;
		$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$model = new ChangePasswordForm();
		
		if($model->load(Yii::$app->request->post()))
        {
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->changePassword($id, $model))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Пароль изменен!');
			}
			
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
			
			return $this->redirect(['profile', 'id' => $id]);
        } 
        else 
        {
			$this->view->params['title'] = Yii::t('form', 'Смена пароля');
			
            return $this->render('change_password'.$this->theme, [
                'model' => $model
            ]);
        }
    }
    
    public function actionStructure()
    {
		$model = new Partners;
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$sponsorAdvertModel = new SponsorAdvert();
		$partnerModel = $model->getPartnerInfo($id);
		$partnersList = $model->getPartnersListByPartnerID($id);
		$partnerscount = (!is_null($partnersList)) ? $partnersList->count() : 0;
		$structureNumber = 1;
		$demo = (!is_null($partnerModel)) ? ((isset($partnerModel['matrix_'.$structureNumber]) && ($partnerModel['matrix_'.$structureNumber] > 0)) ? false : true) : false; 
		$isoList = IsoHelper::getIsoList('en');
		$mainModalWindow = \Yii::$app->session->get('user.main_modal_window');
		$modalContent = ($mainModalWindow > 0) ? (!is_null(StaticContent::find()->where(['name'=>'structure_page_modal']))) ? StaticContent::find()->where(['name'=>'structure_page_modal'])->one() : '' : '';
		$newsAttention = (!is_null(StaticContent::find()->where(['name'=>'news_attention']))) ? StaticContent::find()->where(['name'=>'news_attention'])->one() : null;
		$sponsorAdvert = ($partnerModel !== null && isset($partnerModel->sponsor_id)) ? $sponsorAdvertModel->getSponsorAdvert($partnerModel->sponsor_id) : null;
		$this->view->params['title'] = Yii::t('form', 'Структура');
		
		$dataProvider = new ActiveDataProvider([
			'query' => $partnersList,
			'sort' =>false,
			'pagination' => [
				'pageSize' => 10,
			],
		]);
		
		return $this->render('structure', [
			'dataProvider' => $dataProvider,
			'partnerscount' => $partnerscount,
			'partnerModel' => $partnerModel,
			'demo' => $demo,
			'isoList' => $isoList,
			'mainModalWindow' => $mainModalWindow,
			'modalContent' => $modalContent,
			'sponsorAdvert' => $sponsorAdvert,
			'newsAttention' => $newsAttention,
			'id' => $id,
		]);
	}
	
	public function actionPartnerInfo()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;		
		
		$model = new Partners;		
		$partnerInfo = $model->getPartnerInfo($id);
		
		if($partnerInfo !== null) 
        {
			$demo = ($partnerInfo['matrix_1'] > 0) ? false : true; 	
			$referalsCount = (!is_null($model::find()->where(['sponsor_id'=>$id]))) ? $model::find()->where(['sponsor_id'=>$id])->count() : 0;
			$demoInvitePayOff = DemoInvitePayOff::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['benefit_partner_id'=>$id])->groupBy('`structure_number`')->asArray()->all();
			$demoInvitePayOff = (!empty($demoInvitePayOff)) ? ArrayHelper::map($demoInvitePayOff, 'matrix_number', 'amount') : [];
			$demoMatrixPayments = DemoMatrixPayments::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['partner_id'=>$id, 'type'=>2])->groupBy('`structure_number`')->asArray()->all();
			$demoMatrixPayments = (!empty($demoMatrixPayments)) ? ArrayHelper::map($demoMatrixPayments, 'structure_number', 'amount') : [];
			$invitePayOff = InvitePayOff::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['benefit_partner_id'=>$id])->groupBy('`structure_number`')->asArray()->all();
			$invitePayOff = (!empty($invitePayOff)) ? ArrayHelper::map($invitePayOff, 'structure_number', 'amount') : [];
			$matrixPayments = MatrixPayments::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['partner_id'=>$id, 'type'=>2])->groupBy('`structure_number`')->asArray()->all();
			$matrixPayments = (!empty($matrixPayments)) ? ArrayHelper::map($matrixPayments, 'structure_number', 'amount') : [];
			$partnerEarningsInfo = Partners::getPartnerEarningsInfo($partnerInfo, $demoInvitePayOff, $demoMatrixPayments, $invitePayOff, $matrixPayments);
			$isoList = IsoHelper::getIsoList('en');
			$goldTokenSum = GoldToken::find()->where(['partner_id'=>$id])->sum('amount');
			$content = (!is_null(StaticContent::find()->where(['name'=>'partner_info']))) ? StaticContent::find()->where(['name'=>'partner_info'])->one() : null;
			$this->view->params['title'] = Yii::t('form', 'Информация о партнере');
			$structureNumber = 1;
			
			$dataProvider = new ActiveDataProvider([
				'query' => $model->getRefferalListByPartnerID($structureNumber, $id),
				'pagination' => [
					'pageSize' => 50,
				],
			]);
			
			return $this->render('partner_info'.$this->theme, [
				'dataProvider' => $dataProvider,
				'model' => $partnerInfo,
				'partnerEarningsInfo' => $partnerEarningsInfo,
				'referalsCount' => $referalsCount,
				'demo' => $demo,
				'isoList' => $isoList,
				'goldTokenSum' => $goldTokenSum,
				'content' => $content,
				'statuses_list' => (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [],
			]);
		}
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
	}
	
	public function actionWithdrawal()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$invitePayOffList = new ActiveDataProvider([
			'query' => InvitePayOff::find()->joinWith(['partner', 'benefitPartner'])->where(['`invite_pay_off`.`benefit_partner_id`'=>$id])->orderBy('`invite_pay_off`.`created_at` DESC'),
			'pagination' => [
				'pageSize' => 2,
			],
		]);
		
		$matrixPaymentsList = new ActiveDataProvider([
			'query' => MatrixPayments::find()->joinWith(['partner', 'payerPartner'])->where(['matrix_payments.partner_id'=>$id, 'matrix_payments.type'=>2])->orderBy('`matrix_payments`.`created_at` DESC'),
			'pagination' => [
				'pageSize' => 2,
			],
		]);
		
		return $this->render('withdrawal', [
			'invitePayOffList' => $invitePayOffList,
			'matrixPaymentsList' => $matrixPaymentsList
		]);
	}
	
	public function actionMatrixPaymentsList()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		self::checkIDInQueryParams($id);
		
		$demo = (isset(Yii::$app->request->queryParams['demo'])) ? Yii::$app->request->queryParams['demo'] : 0;
		$structure = (isset(Yii::$app->request->queryParams['structure'])) ? Yii::$app->request->queryParams['structure'] : 0;
		$id = (isset(Yii::$app->request->queryParams['id'])) ? Yii::$app->request->queryParams['id'] : 0;
		
		$searchModel = ($demo > 0) ? new DemoMatrixPaymentsSearch() : new MatrixPaymentsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $structure, $id);
		$dataProvider->pagination->pageSize = Service::getPageSize();
		$this->view->params['title'] = Yii::t('form', 'Заработок по структуре');
		
		return $this->render('matrix_payments_list'.$this->theme, [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'demo' => $demo
		]);
	}
	
	public function actionInvitePayoffList()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		self::checkIDInQueryParams($id);
		
		$demo = (isset(Yii::$app->request->queryParams['demo'])) ? Yii::$app->request->queryParams['demo'] : 0;
		$structure = (isset(Yii::$app->request->queryParams['structure'])) ? Yii::$app->request->queryParams['structure'] : 0;
		$id = (isset(Yii::$app->request->queryParams['id'])) ? Yii::$app->request->queryParams['id'] : 0;
		
		$searchModel = ($demo > 0) ? new DemoInvitePayOffSearch() : new InvitePayOffSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $structure, $id);
		$dataProvider->pagination->pageSize = Service::getPageSize();
		$this->view->params['title'] = Yii::t('form', 'Список выплат за личные приглашения');
		
		return $this->render('invite_payoff_list'.$this->theme, [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'demo' => $demo
		]);
	}
	
	/**
     * Lists all Balls models.
     * @return mixed
     */
    public function actionBallsList()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		self::checkIDInQueryParams($id);
		
        $searchModel = new BallsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];

        return $this->render('balls_list'.$this->theme, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList
        ]);
    }
	
	public function actionTopLeaders()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$model = new TopReferals;
		$isoList = IsoHelper::getIsoList('en');
		$dataProvider = new ActiveDataProvider([
			'query' => $model->getTopLeaders(true),
			'pagination' => [
				'pageSize' => 100,
			],
		]);
		$dataProvider->pagination = false;
		$content = (!is_null(StaticContent::find()->where(['name'=>'top_leaders']))) ? StaticContent::find()->where(['name'=>'top_leaders'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'TOP Активных');
		
		return $this->render('top_leaders'.$this->theme, [
			'dataProvider' => $dataProvider,
			'isoList' => $isoList,
			'content' => $content,
		]);
	}
	
	public function actionMatrices()
    {
		//$this->layout = 'matrices';
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		if($id > 0)
		{
			$model = new Partners;
			$partnerInfo = $model->getPartnerInfo($id, true);
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			
			if(!empty($partnerInfo) && !empty($structuresList))
			{
				$matricesSettingsList = Matrix::getAllMatricesSettingsInProject();
				$list_view_count = (isset(\Yii::$app->params['list_view_count'])) ? \Yii::$app->params['list_view_count'] : 0;
				$demoLevels = DemoLevelsPayment::find()->select('DISTINCT `level`')->where(['partner_id'=>$id])->orderBy('level ASC')->all();
				$levels = LevelsPayment::find()->select('DISTINCT `level`')->where(['partner_id'=>$id])->orderBy('level ASC')->all();
				$content = (!is_null(StaticContent::find()->where(['name'=>'matrices_content']))) ? StaticContent::find()->where(['name'=>'matrices_content'])->one() : null;
				$this->view->params['title'] = Yii::t('form', 'Ваш заработок');
				
				return $this->render('matrices_structure'.$this->theme, [
					'demoLevels' => $demoLevels,
					'levels' => $levels,
					'model' => $partnerInfo,
					'structures_list' => $structuresList,
					'list_view_count' => $list_view_count,
					'matrices_settings_list' => $matricesSettingsList,
					'content' => $content,
					'id' => $id,
					'admin' => true,
					'theme' => $this->theme,
				]);
			}
		}
	}
	
	public function actionPlatform($number, $type)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$demo = ($type > 0) ? true : false;
		$partnerInfo = Partners::find()->where(['id'=>$id])->one();
		$data = Matrix::getPlatformDataByNumber($number, $id, $demo);
		
		return $this->render('platform', [
			'data' => $data,
			'model' => $partnerInfo,
			'id' => $id,
			'admin' => false,
			'number' => $number,
			'demo' => $demo,
		]);
	}
	
	public function actionPartnersLevels($type)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$model = new Partners;
		$partnerModel = $model->getPartnerInfo($id);
		
		$model = new Matrix;
		$demo = ($type > 0) ? true : false;
		$content = (!is_null(StaticContent::find()->where(['name'=>(($type > 0) ? 'demo_structure' : 'real_structure')]))) ? StaticContent::find()->where(['name'=>(($type > 0) ? 'demo_structure' : 'real_structure')])->one() : null;
		$levels = (isset(Yii::$app->params['structure_levels'])) ? Yii::$app->params['structure_levels'] : [];
		$this->view->params['title'] = ($type > 0) ? Yii::t('form', 'Ожидаемый заработок') : Yii::t('form', 'Рельный заработок');
		
		return $this->render('partners-levels', [
			'content' => $content,
			'demo' => $demo,
			'levels' => $levels,
		]);
	}
	
	public function actionPartnersLevel($id, $level, $credit, $demo)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$this->view->params['title'] = Yii::t('form', 'Уровень №').$level;
		$partnerModel = $this->findModel($id);
		$dataProvider = null;
		$isoList = [];
		$percent = 0;
		
		if($partnerModel !== null)
		{	
			$matrixModel = new Matrix;
			$query = $matrixModel->getLevelPaymentsByLevel($id, $level, $demo, $credit);
			
			if($query !== null)
			{
				$dataProvider = new ActiveDataProvider([
					'query' => $query,
					'pagination' => [
						'pageSize' => 50,
					],
				]);
				
				$isoList = IsoHelper::getIsoList('en');
				$percent = (LevelsPecentage::find()->where(['level'=>$level])->one() !== null) ? LevelsPecentage::find()->where(['level'=>$level])->one()->value : 0;		
			}
		}
		
		return $this->render('level', [
			'dataProvider' => $dataProvider,
			'partnerModel' => $partnerModel,
			'id' => $id,
			'admin' => false,
			'number' => $level,
			'isoList' => $isoList,
			'percent' => $percent,
			'demo' => $demo,
		]);
	}
	
	public function actionMatrixLevel($structure, $matrix, $demo, $matrix_id, $level)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$data = Matrix::getMatrixDataByLevel($structure, $matrix, $matrix_id, $level, $demo);
		
		return $this->render('matrix_by_level', [
			'data' => $data,
			'id' => $id,
			'structure_number' => $structure,
			'matrix' => $matrix,
			'demo' => $demo,
		]);
	}
	
	public function actionMatrixById($structure, $number, $demo, $matrix_id, $partner_id)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$partnerModel = $this->findModel($partner_id);
		
		if($partnerModel !== null)
		{	
			$data = [];
			$matricesSettings = Matrix::getMatricesSettings($structure, $number);
			$goldTokenList = GoldToken::find()->select(['matrix_id', 'amount'])->where(['partner_id'=>$matrix_id, 'structure_number'=>$structure, 'matrix'=>$number])->asArray()->all();
			$goldTokenList = (!empty($goldTokenList)) ? ArrayHelper::map($goldTokenList, 'matrix_id', 'amount') : [];
			$data = (!empty($matricesSettings)) ? Matrix::getMatrixDataByID($structure, $number, $matrix_id, $demo) : [];
			
			return $this->render('matrix_by_id', [
				'data' => $data,
				'model' => $partnerModel,
				'id' => $matrix_id,
				'admin' => false,
				'structure_number' => $structure,
				'number' => $number,
				'demo' => $demo,
				'matrix_wide' => $matricesSettings['wide'],
				'pay_off' => $matricesSettings['pay_off'],
				'matrix_type' => $matricesSettings['type'],
				'gold_token' => (isset($gold_token_list[$id]) ? $gold_token_list[$id] : 0),
			]);
		}
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
	}
	
	public function actionPartnersMatrix($id, $structure, $number, $demo, $list_view)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$partnerModel = $this->findModel($id);
		$data = [];
		
		if($partnerModel !== null)
		{
			$goldTokenList = GoldToken::find()->select(['matrix_id', 'amount'])->where(['partner_id'=>$id, 'matrix'=>$number])->asArray()->all();
			$goldTokenList = (!empty($goldTokenList)) ? ArrayHelper::map($goldTokenList, 'matrix_id', 'amount') : [];
			$matricesSettings = Matrix::getMatricesSettings($structure, $number);
			$data = (!empty($matricesSettings)) ? (($list_view > 0) ? Matrix::getPlatformDataByNumberInListView($structure, $number, $id, $matricesSettings['type'], $matricesSettings['levels'], $demo, $matricesSettings['account_type']) : Matrix::getPlatformDataByNumber($structure, $number, $id, $matricesSettings['type'], $matricesSettings['levels'], $demo, $matricesSettings['account_type'])) : [];
			$this->view->params['title'] = Yii::t('form', 'Площадки');
			
			return $this->render('matrix'.$this->theme, [
				'data' => $data,
				'model' => $partnerModel,
				'id' => $id,
				'admin' => false,
				'number' => $number,
				'demo' => $demo,
				'list_view' => $list_view,
				'structure_number' => $structure,
				'pay_off' => $matricesSettings['pay_off'] ?? '',
				'matrix_wide' => $matricesSettings['wide'] ?? '',
				'gold_token_list' => $goldTokenList,
				'theme' => $this->theme,
			]);
		}
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
	}
	
	public function actionBanners()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;		
		
		$category = 'banners_advert';
		$url = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::getAlias('@banners_advert').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR : '';
		$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
		$this->view->params['title'] = Yii::t('form', 'Баннеры');
		
		return $this->render('banners'.$this->theme, [
			'category' => $category,
			'url' => $url,
			'thumbnail' => $thumbnail,
			'theme' => $this->theme,
		]);
	}
	
	public function actionReferalsLinks()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;		
		$content = (!is_null(StaticContent::find()->where(['name'=>'referals-links']))) ? StaticContent::find()->where(['name'=>'referals-links'])->one() : '';
		$this->view->params['title'] = Yii::t('form', 'Реферальные ссылки');
		
		return $this->render('ref_links'.$this->theme, [
			'content' => $content
		]);
	}
	
	public function actionContacts()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$model = new FeedbackForm();
		$content = (!is_null(StaticContent::find()->where(['name'=>'contacts_content']))) ? StaticContent::find()->where(['name'=>'contacts_content'])->one() : '';
		$this->view->params['title'] = Yii::t('form', 'Контакты');
		
		return $this->render('contacts', [
			'model' => $model,
			'content' => $content,
		]);
	}
	
	public function actionBackofficeContent($name)
    {	
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$staticContent = (!is_null(StaticContent::find()->where(['name'=>$name]))) ? StaticContent::find()->where(['name'=>$name])->one() : '';
		
        return $this->render('backoffice-content', [
			'staticContent' => $staticContent,
		]);
    }
    
    public function actionTopLeaderInfo()
    {	
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		if(\Yii::$app->session->get('user.top_leader'))
		{
			$content = (!is_null(StaticContent::find()->where(['name'=>'top_leader_info']))) ? StaticContent::find()->where(['name'=>'top_leader_info'])->one() : '';
			$this->view->params['title'] = Yii::t('form', 'Информация для лидеров');
			$partnerModel = $this->findModel($id);
			
			return $this->render('top_leader_info'.$this->theme, [
				'model' => $partnerModel,
				'content' => $content,
				'id' => $id,
			]);
		}
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
        }
    }
    
    /**
     * Displays a single Partners model.
     * @param integer $id
     * @return mixed
     */
    public function actionMarketingPlan()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$leftContent = (!is_null(StaticContent::find()->where(['name'=>'marketing-plan-left']))) ? StaticContent::find()->where(['name'=>'marketing-plan-left'])->one() : null;
		$rightContent = (!is_null(StaticContent::find()->where(['name'=>'marketing-plan-right']))) ? StaticContent::find()->where(['name'=>'marketing-plan-right'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'План вознаграждений');
		
		return $this->render('marketing_plan'.$this->theme, [
            'leftContent' => $leftContent,
            'rightContent' => $rightContent,
        ]);
    }
    
    /**
     * Displays a single Partners model.
     * @param integer $id
     * @return mixed
     */
    public function actionActivation()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$partnerModel = $this->findModel($id);
		$content = (!is_null(StaticContent::find()->where(['name'=>'activation']))) ? StaticContent::find()->where(['name'=>'activation'])->one() : null;
		$tileContent = (!is_null(StaticContent::find()->where(['name'=>'tile_content']))) ? StaticContent::find()->where(['name'=>'tile_content'])->one() : null;
		$paymentPackages = (isset(Yii::$app->params['payment_packages'])) ? Yii::$app->params['payment_packages'] : [];
		$this->view->params['title'] = Yii::t('form', 'Активация');
			
		if(\Yii::$app->session->get('activation'))
		{
			\Yii::$app->session->remove('activation');
		}
			
		return $this->render('activation'.$this->theme, [
			'model' => $partnerModel,
			'content' => $content,
			'payment_packages' => $paymentPackages,
			'tile_content' => $tileContent,
			'id' => $id,
		]);
    }
    
     /**
     * Displays a request data.
     * @param integer $id
     * @return mixed
     */
    public function actionPay($id, $payment_type, $structure, $matrix, $places)
    {
		$this->layout = 'blank';
		$this->user_id = $id;
		$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		$page = false;
		
		if(Service::isActionAllowed('is_activation_allowed'))
		{
			$sessionID = Service::getSessionID();
			
			if((!empty($paymentsTypes)) && $id > 0 && $payment_type > 0 && $structure > 0 && $matrix > 0 && $places > 0 && $sessionID != '')
			{	
				$activationSession = [];
				$activationSession[$sessionID]['partner_id'] = $id;
				$activationSession[$sessionID]['structure'] = $structure;
				$activationSession[$sessionID]['matrix'] = $matrix;
				$activationSession[$sessionID]['places'] = $places;
				$activationSession[$sessionID]['payment_type'] = $payment_type;
				
				\Yii::$app->session->set('activation', $activationSession);
				
				$activationSession = \Yii::$app->session->get('activation');
				
				if(
					(isset($activationSession[$sessionID]['partner_id']) && $activationSession[$sessionID]['partner_id'] > 0)
					&& (isset($activationSession[$sessionID]['structure']) && $activationSession[$sessionID]['structure'] > 0)
					&& (isset($activationSession[$sessionID]['matrix']) && $activationSession[$sessionID]['matrix'] > 0)
					&& (isset($activationSession[$sessionID]['places']) && $activationSession[$sessionID]['places'] > 0)
					&& (isset($activationSession[$sessionID]['payment_type']) && $activationSession[$sessionID]['payment_type'] > 0)
				)
				{	
					$model = $this->findModel($id);
					
					if($model->matrix_1 == 0 && $matrix > 1)
					{
						\Yii::$app->getSession()->setFlash('error', Yii::t('messages', Yii::t('messages', 'Вы не активированы!')));
						
						return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/activation');
					}
					
					$matricesSettings = Matrix::getMatricesSettings($structure, $matrix);
					$activationAmount = (!empty($matricesSettings)) ? $matricesSettings['pay'] : 0;
					$content = (!is_null(StaticContent::find()->where(['name'=>'activation_content']))) ? StaticContent::find()->where(['name'=>'activation_content'])->one() : null;
					
					if($activationAmount > 0)
					{
						$activationAmount*= $places;
					}
					
					if($activationAmount > 0)
					{
						$paymentsInvoiceID = Payment::getInvoiceID();
						
						if($paymentsInvoiceID > 0)
						{	
							$activationSession[$sessionID]['order_id'] = $paymentsInvoiceID;
							$activationSession[$sessionID]['amount'] = $activationAmount;
				
							\Yii::$app->session->set('activation', $activationSession);
							
							$activationSession = \Yii::$app->session->get('activation');
							
							if(
								(isset($activationSession[$sessionID]['order_id']) && $activationSession[$sessionID]['order_id'] > 0)
								&& (isset($activationSession[$sessionID]['amount']) && $activationSession[$sessionID]['amount'] > 0)
							)
							{	
								return $this->redirect(\Yii::$app->request->BaseUrl.'/payeer/make-payment');
							}
						}
					}
				}
			}
		}
		else
		{
			throw new NotFoundHttpException(Yii::t('messages', 'Активация не доступна!'));
		}
	}
	
	/**
     * Displays a request data.
     * @param integer $id
     * @return mixed
     */
    public function actionReservePlaces($id, $payment_type)
    {
		if(Service::isActionAllowed('is_activation_allowed'))
		{
			$this->user_id = $id;
			$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
			$structureNumber = intval(Yii::$app->request->get('structure'));
			$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			$page = false;
			
			if((!empty($structuresList)) && (!empty($paymentsTypes)) && $id > 0 && $payment_type > 0)
			{
				$partnerModel = $this->findModel($id);
				
				if($partnerModel != null)
				{		
					$model = new ReserveForm();
					$model->scenario = ('front');
					$matricesList = ArrayHelper::map(Matrix::getAllMatricesSettings($structureNumber), 'number', 'number');
					$matricesList = (!empty($matricesList)) ? array_map(function($val) { return Yii::t('form', 'Матрица').' - '.$val; }, $matricesList) : $matricesList;
					$content = (!is_null(StaticContent::find()->where(['name'=>'reserve_content']))) ? StaticContent::find()->where(['name'=>'reserve_content'])->one() : null;
					$this->view->params['title'] = Yii::t('form', 'Активация');
					$page = true;
					
					if($model->load(Yii::$app->request->post()))
					{
						if($model->validateData($model))
						{	
							return $this->redirect(['pay', 
								'id' => $id,
								'payment_type' => $payment_type,
								'structure' => $model->structure,
								'matrix' => $model->matrix,
								'places' => $model->places_count,
							]);	
						}
					}
				}
			}
			
			if($page)
			{
				return $this->render('reserve'.$this->theme, [
					'partnerModel' => $partnerModel,
					'model' => $model,
					'structure' => $structureNumber,
					'partner_id' => $id,
					'max_matrix' => (isset(Yii::$app->params['matrices_count'])) ? Yii::$app->params['matrices_count'] : 0,
					'statuses_list' => (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [],
					'matrices_list' => $matricesList,
					'structures_list' => $structuresList,
				]);
			}
			else
			{
				throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
			}
		}
		else
		{
			throw new NotFoundHttpException(Yii::t('messages', 'Активация не доступна!'));
		}
	}
	
	public function actionPaymentPageByCount($id, $type, $count)
    {
		$this->user_id = $id;
		$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$model = $this->findModel($id);
		
		if($id > 0 && $type > 0)
		{
			$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
			$activationAmount = Service::getActivationAmount($count);
			$content = (!is_null(StaticContent::find()->where(['name'=>'activation_content']))) ? StaticContent::find()->where(['name'=>'activation_content'])->one() : null;
			
			\Yii::$app->session->set('activation.partner_id', $id);
			\Yii::$app->session->set('activation.count', $count);
			\Yii::$app->session->set('activation.payment_type', $type);
        
			if(\Yii::$app->session->get('activation.partner_id') || \Yii::$app->session->get('activation.count') || \Yii::$app->session->get('activation.payment_type'))
			{
				return $this->render('payment_page', [
					'model' => $model,
					'activation_amount' => $activationAmount,
					'paymentsTypes' => $paymentsTypes,
					'type' => $type,
					'id' => $id,
					'content' => $content,
				]);
			}
		}
		
		throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
	}
    
    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Partners::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
        }
    }
    
    protected static function checkIDInQueryParams($id)
    {
		if(!isset($_GET['id']) || !isset($_GET['structure']))
		{
			throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
		}
		else
		{
			$paramsID = intval($_GET['id']);
			
			if($id != $paramsID)
			{
				throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
			}
		}
	}
}
