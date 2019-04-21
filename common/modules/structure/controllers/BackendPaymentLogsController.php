<?php
namespace common\modules\structure\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\structure\models\PaymentLogs;
use common\modules\structure\models\PaymentLogsSearch;

/**
 * BackendPaymentLogsController implements the CRUD actions for PaymentLogs model.
 */
class BackendPaymentLogsController extends Controller
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
		
        $searchModel = new PaymentLogsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList,
        ]);
    }
    
    /**
     * Displays a single PaymentsFaul model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
		$model = new PaymentLogs();
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		$accountTypesList = (isset(\Yii::$app->params['account_types'])) ? \Yii::$app->params['account_types'] : [];
		
        return $this->render('view', [
            'model' => $model->getLogBydID($id),
            'paymentTypes' => Yii::$app->params['payments_types'],
            'structuresList' => $structuresList,
            'accountTypesList' => $accountTypesList,
        ]);
    }
}
