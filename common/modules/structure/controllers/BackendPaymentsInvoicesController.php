<?php

namespace common\modules\structure\controllers;

use Yii;
use common\modules\structure\models\PaymentsInvoices;
use common\modules\structure\models\PaymentsInvoicesSearch;
use common\components\advacash\models\Advacash;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackendPaymentsInvoicesController implements the CRUD actions for PaymentsInvoices model.
 */
class BackendPaymentsInvoicesController extends Controller
{
	protected $permission;
	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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
     * Lists all PaymentsInvoices models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new PaymentsInvoicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'structuresList' => $structuresList,
            'accountTypes' => Yii::$app->params['account_types']
        ]);
    }

    /**
     * Displays a single PaymentsInvoices model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
		$model = new PaymentsInvoices();
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
        return $this->render('view', [
			'model' => $model->getInvoiceBydID($id),
            'paymentTypes' => Yii::$app->params['payments_types'],
            'accountTypes' => Yii::$app->params['account_types'],
            'structuresList' => $structuresList,
        ]);
    }
    
    public function actionPaymentHistory()
    {
		$model = new Advacash();
		$result = $model->getHistory();
		echo '<pre>';
		print_r($result);
		echo '</pre>';
		
	}

    /**
     * Finds the PaymentsInvoices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentsInvoices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentsInvoices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
