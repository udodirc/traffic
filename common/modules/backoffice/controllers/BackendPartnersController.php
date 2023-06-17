<?php
namespace common\modules\backoffice\controllers;

use common\modules\structure\models\TopReferalsSearch;
use Yii;
use common\modules\structure\models\MatrixStructureSearch;
use common\modules\structure\models\TopReferals;
use common\modules\structure\models\Withdrawal;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\MatrixPaymentsSearch;
use common\modules\structure\models\DemoMatrixPaymentsSearch;
use common\modules\structure\models\DemoMatrixPayments;
use common\modules\structure\models\DemoInvitePayOff;
use common\modules\structure\models\DemoInvitePayOffSearch;
use common\modules\structure\models\InvitePayOff;
use common\modules\structure\models\InvitePayOffSearch;
use common\modules\structure\models\AdminPayOff;
use common\modules\structure\models\AdminPayOffSearch;
use common\modules\structure\models\forms\ReserveForm;
use common\modules\structure\models\forms\SetMatrixForm;
use common\modules\structure\models\forms\ActivateForm;
use common\modules\structure\models\CreditDemoLevelsPayment;
use common\modules\structure\models\DemoLevelsPayment;
use common\modules\structure\models\LevelsPayment;
use common\modules\structure\models\LevelsPecentage;
use common\modules\structure\models\MatricesSettings;
use common\modules\structure\models\DemoBalls;
use common\modules\structure\models\Balls;
use common\modules\structure\models\GoldTokenSettings;
use common\modules\structure\models\GoldToken;
use common\modules\structure\models\PaymentsInvoices;
use common\modules\structure\models\forms\ChangePartnerForm;
use common\modules\backoffice\models\PartnersSearch;
use common\modules\backoffice\models\Partners;
use common\modules\backoffice\models\forms\ActivatePartnersForm;
use common\modules\backoffice\models\forms\ChangeSponsorForm;
use common\modules\backoffice\models\forms\SignupForm;
use common\modules\backoffice\models\forms\StructurePartnersForm;
use common\modules\backoffice\models\forms\SetBonusForm;
use common\models\Service;
use common\components\geo\IsoHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * BackendPartnersController implements the CRUD actions for Partners model.
 */
class BackendPartnersController extends Controller
{
	public $layout = 'backend';
	protected $permission;
	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ]
        ];
    }
    
    /**
     * Check permission's rights
     * @return mixed
    */
    public function afterAction($action, $result)
    {
        if(!\Yii::$app->user->can($this->permission)) 
		{
			throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
		}
        
        return parent::afterAction($action, $result);
    }

    /**
     * Lists all Partners models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$searchModel = new PartnersSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
        $countryList = IsoHelper::getIsoList('en');
        
		return $this->render('index', [
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'countryList' => $countryList,
		]);
    }
    
    public function actionPartnerInfo($id)
    {
		$this->permission = 'view';
		
		$model = new Partners;
		$partnerInfo = $model->getPartnerInfo($id);
		
		if(!empty($partnerInfo)) 
        {
			$referalsCount = $model::find()->where(['sponsor_id'=>$id])->count();
			$totalRealPaid = PaymentsInvoices::find()->where(['partner_id'=>$id, 'account_type'=>2])->sum('`amount`');
			$totalRealPayments = PaymentsInvoices::find()->where(['partner_id'=>$id, 'account_type'=>1])->sum('`amount`');
			$demoInvitePayOff = DemoInvitePayOff::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['benefit_partner_id'=>$id])->groupBy('`structure_number`')->asArray()->all();
			$demoInvitePayOff = (!empty($demoInvitePayOff)) ? ArrayHelper::map($demoInvitePayOff, 'matrix_number', 'amount') : [];
			$demoMatrixPayments = DemoMatrixPayments::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['partner_id'=>$id, 'type'=>2])->groupBy('`structure_number`')->asArray()->all();
			$demoMatrixPayments = (!empty($demoMatrixPayments)) ? ArrayHelper::map($demoMatrixPayments, 'structure_number', 'amount') : [];
			$invitePayOff = InvitePayOff::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['benefit_partner_id'=>$id])->groupBy('`structure_number`')->asArray()->all();
			$invitePayOff = (!empty($invitePayOff)) ? ArrayHelper::map($invitePayOff, 'structure_number', 'amount') : [];
			$matrixPayments = MatrixPayments::find()->select(['`structure_number`, SUM(`amount`) as `amount`'])->where(['partner_id'=>$id, 'type'=>2])->groupBy('`structure_number`')->asArray()->all();
			$matrixPayments = (!empty($matrixPayments)) ? ArrayHelper::map($matrixPayments, 'structure_number', 'amount') : [];
			$goldTokenSum = GoldToken::find()->where(['partner_id'=>$id])->sum('amount');
			$matricesList = Partners::getTotalMatricesByPartner($id);
			$partnerEarningsInfo = Partners::getPartnerEarningsInfo($partnerInfo, $demoInvitePayOff, $demoMatrixPayments, $invitePayOff, $matrixPayments, true);
			$dataProvider = new ActiveDataProvider([
				'query' => $model::find()->where(['sponsor_id'=>$id]),
				'pagination' => [
					'pageSize' => 50,
				],
			]);
			$geoData = ($partnerInfo['geo'] != '') ? unserialize($partnerInfo['geo']) : [];
			
            return $this->render('partner_info', [
				'dataProvider' => $dataProvider,
				'model' => $partnerInfo,
				'referalsCount' => $referalsCount,
				'totalRealPaid' => $totalRealPaid,
				'totalRealPayments' => $totalRealPayments,
				'matricesList' => $matricesList,
				'partnerEarningsInfo' => $partnerEarningsInfo,
				'goldTokenSum' => $goldTokenSum,
				'geoData' => $geoData,
				'id' => $id,
				'statuses_list' => (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [],
				'defaultWallet' => (isset(\Yii::$app->params['default_payment_wallet'])) ? \Yii::$app->params['default_payment_wallet'] : '',
			]);
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
	}
	
	public function actionInvitePayoffList()
    {
		$this->permission = 'view';
		
		$demo = (isset(Yii::$app->request->queryParams['demo'])) ? Yii::$app->request->queryParams['demo'] : 0;
		$structure = (isset(Yii::$app->request->queryParams['structure'])) ? Yii::$app->request->queryParams['structure'] : 0;
		$id = (isset(Yii::$app->request->queryParams['id'])) ? Yii::$app->request->queryParams['id'] : 0;
		
		$searchModel = ($demo > 0) ? new DemoInvitePayOffSearch() : new InvitePayOffSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $structure, $id);
		$dataProvider->pagination->pageSize = Service::getPageSize();
		
		return $this->render('invite_payoff_list', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'demo' => $demo
		]);
	}
	
	public function actionAdminPayoffList()
    {
		$this->permission = 'view';
		
		$demo = (isset(Yii::$app->request->queryParams['demo'])) ? Yii::$app->request->queryParams['demo'] : 0;
		$structure = (isset(Yii::$app->request->queryParams['structure'])) ? Yii::$app->request->queryParams['structure'] : 0;
		$id = (isset(Yii::$app->request->queryParams['id'])) ? Yii::$app->request->queryParams['id'] : 0;
		
		$searchModel = ($demo > 0) ? new DemoAdminPayOffSearch() : new AdminPayOffSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $structure, $id);
		$dataProvider->pagination->pageSize = Service::getPageSize();
		
		return $this->render('admin_payoff_list', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'demo' => $demo
		]);
	}
	
	public function actionReferralBallsList($id, $demo)
    {
		$this->permission = 'view';
		
		$model = ($demo > 0) ? new DemoBalls : new Balls;
		$table = ($demo > 0) ? 'demo_' : '';
		$dataProvider = new ActiveDataProvider([
			'query' => $model::find()->joinWith(['referral'])->where([$table.'balls.partner_id'=>$id, $table.'balls.type'=>1]),
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		
		return $this->render('referral_balls_list', [
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionStructure($id)
    {
		$this->permission = 'view';
		
		if($id > 0)
		{	
			$model = new Partners();
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
			if(!empty($structuresList))
			{
				$partnerInfo = $model->getPartnerStructureByID($id);
				
				if(!empty($partnerInfo))
				{
					$matricesSettingsList = Matrix::getAllMatricesSettingsInProject();
					$list_view_count = (isset(\Yii::$app->params['list_view_count'])) ? \Yii::$app->params['list_view_count'] : 0;
					
					return $this->render('structure', [
						'model' => $partnerInfo,
						'structures_list' => $structuresList,
						'list_view_count' => $list_view_count,
						'matrices_settings_list' => $matricesSettingsList,
						'id' => $id,
						'admin' => true,
					]);
				}
			}
		}
	}
	
	public function actionDemoStructure($id)
    {
		$this->permission = 'view';
		
		$structureType = (isset(\Yii::$app->params['structure_type'])) ? \Yii::$app->params['structure_type'] : 0;
		
		if($structureType > 0)
		{
			$partnerInfo = Partners::find()->where(['id'=>$id])->one();
			
			if($structureType > 1)
			{
				$matrix = (!is_null($partnerInfo)) ? $partnerInfo->demo_matrix : 0;
				$data = Matrix::getAllMatricesBySponsorID($matrix, $id, true);
				
				return $this->render('structure', [
					'data' => $data,
					'model' => $partnerInfo,
					'id' => $id,
					'admin' => true,
					'demo' => true,
				]);
			}
			else
			{
				$levels = (isset(Yii::$app->params['structure_levels'])) ? Yii::$app->params['structure_levels'] : [];
		
				return $this->render('partners-levels', [
					'model' => $partnerInfo,
					'id' => $id,
					'demo' => true,
					'levels' => $levels,
				]);
				
			}
		}
	}
	
	public function actionPartnersLevel($id, $level, $credit, $demo)
    {
		$this->permission = 'view';
		
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
		]);
	}
	
	public function actionPartnersMatrix($id, $structure, $number, $demo, $list_view)
    {
		$this->permission = 'view';
		
		$partnerModel = $this->findModel($id);
		
		if($partnerModel !== null)
		{	
			$data = [];
			$matricesSettings = Matrix::getMatricesSettings($structure, $number);
			$goldTokenList = GoldToken::find()->select(['matrix_id', 'amount'])->where(['partner_id'=>$id, 'structure_number'=>$structure, 'matrix'=>$number])->asArray()->all();
			$goldTokenList = (!empty($goldTokenList)) ? ArrayHelper::map($goldTokenList, 'matrix_id', 'amount') : [];
			$data = (!empty($matricesSettings)) ? (($list_view > 0) ? Matrix::getPlatformDataByNumberInListView($structure, $number, $id, $matricesSettings['type'], $matricesSettings['levels'], $demo, $matricesSettings['account_type']) : Matrix::getPlatformDataByNumber($structure, $number, $id, $matricesSettings['type'], $matricesSettings['levels'], $demo, $matricesSettings['account_type'])) : [];
			
			return $this->render('matrix', [
				'data' => $data,
				'model' => $partnerModel,
				'id' => $id,
				'admin' => false,
				'structure_number' => $structure,
				'number' => $number,
				'demo' => $demo,
				'list_view' => $list_view,
				'matrix_wide' => $matricesSettings['wide'],
				'pay_off' => $matricesSettings['pay_off'],
				'matrix_type' => $matricesSettings['type'],
				'gold_token_list' => $goldTokenList,
			]);
		}
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
	}
	
	public function actionMatrixLevel($structure, $matrix, $demo, $id, $level)
    {
		$this->permission = 'view';
		
		$data = Matrix::getMatrixDataByLevel($structure, $matrix, $id, $level, $demo);
		
		return $this->render('matrix_by_level', [
			'data' => $data,
			'id' => $id,
			'structure_number' => $structure,
			'matrix' => $matrix,
			'demo' => $demo,
		]);
	}
	
	public function actionMatrixById($structure, $number, $demo, $id, $partner_id)
    {
		$this->permission = 'view';
		
		$partnerModel = $this->findModel($partner_id);
		
		if($partnerModel !== null)
		{	
			$data = [];
			$matricesSettings = Matrix::getMatricesSettings($structure, $number);
			$goldTokenList = GoldToken::find()->select(['matrix_id', 'amount'])->where(['matrix_id'=>$id, 'structure_number'=>$structure, 'matrix'=>$number])->asArray()->all();
			$goldTokenList = (!empty($goldTokenList)) ? ArrayHelper::map($goldTokenList, 'matrix_id', 'amount') : [];
			$data = (!empty($matricesSettings)) ? Matrix::getMatrixDataByID($structure, $number, $id, $demo) : [];
			
			return $this->render('matrix_by_id', [
				'data' => $data,
				'model' => $partnerModel,
				'id' => $id,
				'admin' => false,
				'structure_number' => $structure,
				'number' => $number,
				'demo' => $demo,
				'matrix_wide' => $matricesSettings['wide'],
				'pay_off' => $matricesSettings['pay_off'],
				'matrix_type' => $matricesSettings['type'],
				'gold_token' => (isset($goldTokenList[$id]) ? $goldTokenList[$id] : 0),
			]);
		}
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
	}
	
	public function actionSponsorMatrix($id, $structure, $number, $demo, $list_view)
    {
		$this->permission = 'view';
		
		$matrixModel = new Matrix();
		$sponsorID = $matrixModel->getSponsorMatrix($id, $structure, $number, $demo);
		
		if($sponsorID > 0)
		{	
			return $this->redirect(['partners-matrix', 'id' => $sponsorID, 'structure' => $structure, 'number' => $number, 'demo' => $demo, 'list_view' => $list_view]);
		}
		else
		{
			throw new NotFoundHttpException(Yii::t('messages', 'Спонсора нет!'));
		}
	}
	
	public function actionSponsorStructure($matrix_number, $id, $matrix_id, $demo)
    {
		$this->permission = 'view';
		
		$matrixModel = new Matrix();
		$demo = ($demo > 0) ? true : false;
		$sponsorID = $matrixModel->getStructureSponsorID($matrix_id, 7, $matrix_number, $demo);
		$sponsorID = (!is_null($sponsorID)) ? $sponsorID['partner_id'] : 0;
		$partnerInfo = Partners::find()->where(['id'=>$sponsorID])->one();
		
		if(!is_null($partnerInfo))
		{
			$matrix = ($demo > 0) ? $partnerInfo->matrix : $partnerInfo->demo_matrix;
			$data = Matrix::getMatrixDataBySponsoID($matrix, $sponsorID, $demo);
		
			return $this->render('structure', [
				'data' => $data,
				'model' => $partnerInfo,
				'id' => $sponsorID,
				'admin' => true,
				'demo' => $demo,
			]);
		}
		else
		{
			 throw new NotFoundHttpException(Yii::t('messages', 'Спонсора нет!'));
		}
	}
	
	public function actionMatrixStructure($structure, $matrix, $demo)
    {
		$this->permission = 'view';
				
		$demo = ($demo > 0) ? true : false;
		$searchModel = new MatrixStructureSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $structure, $matrix, $demo);
		$dataProvider->pagination->pageSize = Service::getPageSize();
		
		return $this->render('matrix_structure', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'matrix' => $matrix,
			'demo' => $demo,
		]);
	}
	
	/**
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
		$model = $this->findModel($id);
        $model->scenario = ('backend_update');
        $model->payeer_wallet = trim($model->payeer_wallet);
        
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
            return $this->redirect(['partner-info', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
                'statuses_list' => (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [],
            ]);
        }
    }
    
    public function actionBan($id)
    {
		$this->permission = 'update';
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
		if(Partners::makeBan($id))
		{
			$class = 'success';
			$msg = Yii::t('messages', 'Запись обновлена!');
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
		return $this->redirect(['partner-info', 'id' => $id]);
	}
    
     /**
     * Activate an existing Partner.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id, $pay)
    {
		$this->permission = 'create';
		
		$partnerModel = $this->findModel($id);
		
		if($partnerModel)
		{		
			$model = new ActivateForm();
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			
			if($model->load(Yii::$app->request->post()))
			{
				$class = 'error';
				$msg = Yii::t('messages', 'Ошибка!');
				$pay = ($pay > 0) ? true : false;
				
				if($model->activatePartner($partnerModel->sponsor_id, $id, $pay))
				{
					$emailFrom = (isset(\Yii::$app->params['email_from'])) ? \Yii::$app->params['email_from'] : '';
					$site = (isset(\Yii::$app->params['site_url'])) ? \Yii::$app->params['site_url'] : '';
					/*$mailResult = \Yii::$app->mailer->compose(['html' => 'activate-html-ru'], ['login' => $partnerModel->login, 'site' => $site])
					->setFrom([\Yii::$app->params['supportEmail'] => $emailFrom])
					->setTo($partnerModel->email)
					->setSubject(Yii::t('messages', 'Platform activated - Активация платформы!'))
					->send();*/
					$mailResult = true;
					
					if($mailResult)
					{
						$class = 'success';
						$msg = Yii::t('messages', 'Партнер активирован!');
					}
				}
				
				\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
				return $this->redirect(['index']);
			}
			
			return $this->render('activate', [
				'partnerModel' => $partnerModel,
				'model' => $model,
				'partner_id' => $id,
				'pay' => $pay,
				'structures_list' => $structuresList,
			]);
		}
		else
		{
			 throw new NotFoundHttpException(Yii::t('messages', 'Такого партнера нет!'));
		}
	}
    
    public function actionChangeAdmin($id, $structure, $number, $demo, $partner_id, $listView = false)
    {
		$this->permission = 'update';
		
		$model = $this->findModel($id);
        
        if($model !== null && $id > 0 && $number > 0) 
        {
			$model = new ChangePartnerForm();
			
			if($model->load(Yii::$app->request->post()))
			{
				$class = 'error';
				$msg = Yii::t('messages', 'Ошибка!');
		
				if($model->changePartner($id, $structure, $number, $demo)) 
				{
					$class = 'success';
					$msg = Yii::t('messages', 'Партнер изменен!');
					$listView = Service::getListViewType($structure, $number);
			
					\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
        
					return $this->redirect(['partners-matrix', 'id' => $partner_id, 'structure' => $structure, 'number' => $number, 'demo' => $demo, 'list_view' => $listView]);
				}
			}
			
			return $this->render('change_partner', [
				'model' => $model
			]);
        } 
        else
		{
			 throw new NotFoundHttpException(Yii::t('messages', 'Такого партнера нет!'));
		}
    }
    
    /**
     * Publish item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStatus($id, $status)
    {
		$this->permission = 'update';
		
		$model = $this->findModel($id);
		$model->status = ($status > 0) ? 0 : 1;
		$model->save(false);  // equivalent to $model->update();
		
		return $this->redirect(['index']);
	}
	
	public function actionTopLeaders()
    {
		$this->permission = 'view';
		
//		$model = new TopReferals;
//		$dataProvider = new ActiveDataProvider([
//			'query' => $model->getTopLeaders(),
//			'pagination' => [
//				'pageSize' => 100,
//			],
//		]);

		$searchModel = new TopReferalsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->post());
		$dataProvider->pagination->pageSize = 100;
		$months = TopReferals::monthList();
		
		return $this->render('top_leaders', [
			'dataProvider' => $dataProvider,
			'model' => $searchModel,
			'months' => $months,
		]);
	}
	
	/**
     * Compare wallets in partners.
     * @return mixed
     */
    public function actionCompareWallets()
    {
		$this->permission = 'view';
		
		$defaultWallet = (isset(\Yii::$app->params['default_payment_wallet']['index'])) ? \Yii::$app->params['default_payment_wallet']['index'] : '';
		$dataProvider = new ActiveDataProvider([
			'query' => Partners::getCompareWalletList($defaultWallet),
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		
		return $this->render('compare_wallets', [
			'dataProvider' => $dataProvider,
			'defaultWallet' => $defaultWallet
		]);
    }
    
    /**
     * Compare wallets in partners.
     * @return mixed
     */
    public function actionPartnersListByWallet($wallet)
    {
		$this->permission = 'view';
		
		$dataProvider = new ActiveDataProvider([
			'query' => Partners::find()->where(['advcash_wallet'=>$wallet]),
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		
		return $this->render('partners_list_by_wallet', [
			'dataProvider' => $dataProvider
		]);
	}
	
	public function actionWithdrawal()
    {
		$this->permission = 'view';
		
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		$withdrawalStatuses = (isset(Yii::$app->params['withdrawal_statuses'])) ? Yii::$app->params['withdrawal_statuses'] : [];
		$dataProvider = new ActiveDataProvider([
			'query' => Withdrawal::find()->joinWith(['partner']),
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		
		return $this->render('withdrawal', [
			'dataProvider' => $dataProvider,
			'paymentsTypes' => $paymentsTypes,
			'withdrawalStatuses' => $withdrawalStatuses,
		]);
	}
	
	public function actionGetStructureMatricesDropDownListByAjax()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$post = Yii::$app->request->post();
		$structureNumber = (isset($post['structure'])) ? intval($post['structure']) : 0;
		$type = (isset($post['type'])) ? intval($post['type']) : 0;
		$matricesList = ArrayHelper::map(Matrix::getAllMatricesSettings($structureNumber), 'number', 'number');
		$dataList = (!empty($matricesList)) ? array_map(function($val) { return Yii::t('form', 'Матрица').' - '.$val; }, $matricesList) : $matricesList;
		$result = '';
		
		if(!empty($dataList))
		{
			$modelform = Matrix::getStructureMatricesDropDownListModel($type);
			
			if($modelform != '')
			{
				$result = Html::dropDownList($modelform.'[matrix]', null, $dataList, [
					'prompt'=>Yii::t('form', 'Выберите матрицу'),
					'style'=> 'width:300px;',
					'class'=>'form-control',
					'id' => 'reserve-matrix'
				]);
			}
		}
		
		return $result;
	}
    
    public function actionReserve($id)
    {
		$this->permission = 'update';
		
		$partnerModel = $this->findModel($id);
		
		if($partnerModel)
		{		
			$model = new ReserveForm();
			$model->scenario = ('backend');
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			
			if($model->load(Yii::$app->request->post()))
			{
				$class = 'error';
				$msg = Yii::t('messages', 'Ошибка!');
				
				if($model->reserve($model))
				{
					$class = 'success';
					$msg = Yii::t('messages', 'Партнер активирован!');
				}
				
				\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
				return $this->redirect(['index']);
			}
			
			return $this->render('reserve', [
				'partnerModel' => $partnerModel,
				'model' => $model,
				'partner_id' => $id,
				'max_matrix' => (isset(Yii::$app->params['matrices_count'])) ? Yii::$app->params['matrices_count'] : 0,
				'structures_list' => $structuresList,
				'statuses_list' => (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [],
			]);
		}
		else
		{
			 throw new NotFoundHttpException(Yii::t('messages', 'Такого партнера нет!'));
		}
	}
	
	public function actionSetMatrix($id)
    {
		$this->permission = 'update';
		
		$partnerModel = $this->findModel($id);
		
		if($partnerModel)
		{		
			$model = new SetMatrixForm();
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			
			if($model->load(Yii::$app->request->post()))
			{
				$class = 'error';
				$msg = Yii::t('messages', 'Ошибка!');
			
				if($model->reserve($model))
				{
					$class = 'success';
					$msg = Yii::t('messages', 'Партнер активирован!');
				}
				
				\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
				return $this->redirect(['index']);
			}
			
			return $this->render('set_matrix', [
				'partnerModel' => $partnerModel,
				'model' => $model,
				'partner_id' => $id,
				'max_matrix' => (isset(Yii::$app->params['matrices_count'])) ? Yii::$app->params['matrices_count'] : 0,
				'statuses_list' => (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [],
				'structures_list' => $structuresList,
			]);
		}
		else
		{
			 throw new NotFoundHttpException(Yii::t('messages', 'Такого партнера нет!'));
		}
	}
	
	public function actionAddPartnerInStructure()
    {
		$this->permission = 'create';
		$model = new SignupForm(['scenario' => 'backend_register']);
		
        if($model->load(Yii::$app->request->post())) 
        {	
			if($model->signup()) 
            {
				\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Вы зарегистрированы! Можете зайти в Личный Кабинет здесь'));
				
				return $this->redirect(['index']);
            }
            
            \Yii::$app->getSession()->setFlash('error', Yii::t('messages', 'Ошибка!'));
        }

        return $this->render('signup', [
            'model' => $model
        ]);
	}
	
	public function actionAddPartnersInStructure()
    {
		$this->permission = 'create';
		$model = new StructurePartnersForm();
		
        if($model->load(Yii::$app->request->post())) 
        {	
			if($model->addPartners()) 
            {
				\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Вы зарегистрированы!'));
				
				return $this->redirect(['index']);
            }
            
            \Yii::$app->getSession()->setFlash('error', Yii::t('messages', 'Ошибка!'));
        }

        return $this->render('add_partners', [
            'model' => $model
        ]);
	}
	
	public function actionActivatePartnersInStructure()
    {
		$this->permission = 'create';
		$model = new ActivatePartnersForm();
		
        if($model->load(Yii::$app->request->post())) 
        {	
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->activatePartners()) 
            {
				$class = 'success';
				$msg = Yii::t('messages', 'Партнер активирован!');
            }
            
            \Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
					
			return $this->redirect(['index']);
        }

        return $this->render('activate_partners', [
            'model' => $model
        ]);
	}
	
	/**
     * Publish item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivateStatus($id)
    {
		$this->permission = 'update';
		
		$model = $this->findModel($id);
		$model->status = Partners::STATUS_ACTIVE;
		$model->save(false);  // equivalent to $model->update();
		
		return $this->redirect(['index']);
	}
	
	/**
     * Publish withdrawal item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionWithdrawalStatus($id, $status)
    {
		$this->permission = 'update';
		
		$model = Withdrawal::findOne($id);
		
		if($model !== null)
		{
			$model->status = ($status > 1) ? Withdrawal::STATUS_REJECT : Withdrawal::STATUS_CONFIRM;
			$model->save(false);  // equivalent to $model->update();
		}
		
		return $this->redirect(['withdrawal']);
	}
	
	/**
     * Deletes an existing Partner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionWithdrawalDelete($id)
    {
		$this->permission = 'delete';
		
		$result = Withdrawal::findOne($id)->delete();
		\Yii::$app->getSession()->setFlash(($result) ? 'success' : 'error', Yii::t('messages', ($result) ? 'Запись удалена!' : 'Ошибка!'));

        return $this->redirect(['index']);
    }
    
    /**
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionChangeSponsor($id)
    {
		$this->permission = 'update';
		
		$model = new ChangeSponsorForm();
		
        if($model->load(Yii::$app->request->post())) 
        {	
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->changeSponsor($id)) 
            {
				$class = 'success';
				$msg = Yii::t('messages', 'Спонсор изменен!');
            }
            
            \Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
					
			return $this->redirect(['index']);
        }

        return $this->render('change_sponsor', [
            'model' => $model
        ]);
    }
    
    public function actionGetMatricesDropDownListByAjax()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$post = Yii::$app->request->post();
		$structureNumber = (isset($post['structure'])) ? intval($post['structure']) : 0;
		$id = (isset($post['id'])) ? intval($post['id']) : 0;
		$matrix = (isset($post['matrix'])) ? intval($post['matrix']) : 0;
		$matricesList = ArrayHelper::map(Matrix::getAllMatricesSettings($structureNumber), 'number', 'number');
		$dataList = Matrix::getMatrixListInBonusMode($structureNumber, $id, count($matricesList));
		$result = '';
		
		if(!empty($dataList))
		{
			$result = Html::dropDownList('SetBonusForm[matrix]', null, $dataList, [
				'prompt'=>Yii::t('form', 'Выберите матрицу'),
				'style'=> 'width:300px;',
				'class'=>'form-control',
				'id' => 'set-bonus-matrix'
			]);
		}
		
		return $result;
	}
    
    public function actionSetBonus($id)
    {
		$this->permission = 'update';
		
		$partnerModel = $this->findModel($id);
		
		if($partnerModel != null)
		{
			$model = new SetBonusForm();
			$structures_list = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			$bonuses_list = ArrayHelper::map(GoldTokenSettings::find()->asArray()->all(), 'id', 'amount');
			
			if($model->load(Yii::$app->request->post())) 
			{	
				$class = 'error';
				$msg = Yii::t('messages', 'Ошибка!');
				
				if($model->setBonus($model->structure, $id, $bonuses_list)) 
				{
					$class = 'success';
					$msg = Yii::t('messages', 'Бонус назначен!');
				}
				
				\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
						
				return $this->redirect(['index']);
			}

			return $this->render('set_bonus', [
				'model' => $model,
				'structures_list' => $structures_list,
				'bonuses_list' => $bonuses_list,
				'id' => $id,
				'matrix' => $partnerModel->matrix_1,
			]);
		}
		else
		{
			 throw new NotFoundHttpException(Yii::t('messages', 'Такого партнера нет!'));
		}
	}
	
	/**
     * Deletes an existing Partner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$this->permission = 'delete';
		
        $result = true;
		\Yii::$app->getSession()->setFlash(($result) ? 'success' : 'error', Yii::t('messages', ($result) ? 'Запись удалена!' : 'Ошибка!'));

        return $this->redirect(['index']);
    }
    
    public function actionCreateDemoStructure()
    {
        $matrix = new Matrix();
        $matrix->createDemoStructure();
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
}
