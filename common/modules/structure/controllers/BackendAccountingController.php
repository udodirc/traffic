<?php
namespace common\modules\structure\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\structure\models\EarningsSearch;
use common\modules\structure\models\EarningsPaymentsListSearch;
use common\modules\structure\models\PaidPartnersSearch;
use common\modules\structure\models\EarningsListSearch;
use common\modules\structure\models\BallsSearch;
use common\modules\structure\models\TransferBallsSearch;
use common\modules\structure\models\PaymentsInvoices;
use common\modules\structure\models\forms\TransferBallsForm;

/**
 * BackendAccountingController implements the CRUD actions for Withdrawal model.
 */
class BackendAccountingController extends Controller
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
     * Lists all Withdrawal models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new PaidPartnersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Withdrawal models.
     * @return mixed
     */
    public function actionEarnedPartners()
    {
		$this->permission = 'view';
		
        $searchModel = new EarningsSearch();
        $stuctureNumber = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $stuctureNumber);
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
		return $this->render('earned_partners', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stuctureNumber' => $stuctureNumber,
            'structuresList' => $structuresList,
            'compare' => false,
        ]);
    }
    
    /**
     * Lists all Earnings payments models.
     * @return mixed
     */
    public function actionEarningsList($id)
    {
		$this->permission = 'view';
		
		$searchModel = new EarningsListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
        return $this->render('earnings_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList,
        ]);
    }
    
    /**
     * Lists all Earnings payments models.
     * @return mixed
     */
    public function actionPaymentsById($id)
    {
		$this->permission = 'view';
		
		$searchModel = new EarningsPaymentsListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id, 1);
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
        return $this->render('earnings_payments_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'structuresList' => $structuresList,
            'accountTypes' => Yii::$app->params['account_types']
        ]);
    }
    
    /**
     * Lists all Earnings payments models.
     * @return mixed
     */
    public function actionComparePayments()
    {
		$this->permission = 'view';
		
        $searchModel = new EarningsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		$stuctureNumber = 1;
		
        return $this->render('earned_partners', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList,
            'stuctureNumber' => $stuctureNumber,
            'compare' => true,
        ]);
    }
    
    /**
     * Lists all Earnings payments models.
     * @return mixed
     */
    public function actionComparePaymentById($id)
    {
		$this->permission = 'view';
		
        echo $id;
        $model = new PaymentsInvoices();
        $model->comparePaymentsWithMatrixPayments($id);
        
    }
    
    /**
     * Lists all Withdrawal models.
     * @return mixed
     */
    public function actionEarningsPaymentList($id)
    {
		$this->permission = 'view';
		
		$searchModel = new EarningsPaymentsListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
        return $this->render('earnings_payments_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'structuresList' => $structuresList,
            'accountTypes' => Yii::$app->params['account_types']
        ]);
    }
    
    /**
     * Lists all Withdrawal models.
     * @return mixed
    */
    public function actionPartnersBallsList()
    {
		$this->permission = 'view';
		
        $searchModel = new BallsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('partners_balls', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Withdrawal models.
     * @return mixed
    */
    public function actionTransferBallsList()
    {
		$this->permission = 'view';
		
        $searchModel = new TransferBallsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('transfer_balls', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Withdrawal models.
     * @return mixed
    */
    public function actionTransferBalls()
    {
		$this->permission = 'create';
		
		$model = new TransferBallsForm();
			
		if($model->load(Yii::$app->request->post()))
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->transferBalls())
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Партнер активирован!');
			}
				
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
					
			return $this->redirect(['transfer-balls-list']);
		}
			
		return $this->render('transfer_balls_form', [
			'model' => $model,
		]);
	}
}
