<?php
namespace common\modules\structure\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\structure\models\DemoMatrixPaymentsSearch;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\MatrixPaymentsSearch;
use common\modules\structure\models\Payment;
use common\models\Service;

/**
 * BackendMatrixPaymentsController implements the CRUD actions for InvitePayOff model.
 */
class BackendMatrixPaymentsController extends Controller
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
     * Lists all MatrixPayments models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$demo = (isset(Yii::$app->request->queryParams['demo'])) ? Yii::$app->request->queryParams['demo'] : 0;
		$structure = (isset(Yii::$app->request->queryParams['structure'])) ? Yii::$app->request->queryParams['structure'] : 0;
		$id = (isset(Yii::$app->request->queryParams['id'])) ? Yii::$app->request->queryParams['id'] : 0;
		
		$searchModel = ($demo > 0) ? new DemoMatrixPaymentsSearch() : new MatrixPaymentsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $structure, $id);
		$dataProvider->pagination->pageSize = Service::getPageSize();
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'demo' => $demo
		]);
    }
    
     /**
     * Mark the payment in MatrixPayments models.
     * @return mixed
     */
    public function actionMarkPayOff($id)
    {
		$this->permission = 'view';
		
		//Initialization
		$result = true;
		$class = 'success';
		$msg = Yii::t('messages', 'Запись обновлена!');
		
		//Set mode
		$model = MatrixPayments::find()->where('id=:id', [':id' => $id])->one();
		
		if($model->paid_off == 0)
		{
			$model->paid_off = 1;
			$result = $model->save(false);
		}
		
		if(!$result)
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
		return $this->redirect(['index']);
	}
	
	/**
     * Make the pay off through wallet.
     * @return mixed
     */
    public function actionMakePayOff($id, $type)
    {
		$this->permission = 'view';
		
		//Initialization
		$url = '';
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes) && $id > 0)
		{
			$paymentType = Payment::getPaymentType();
			
			if(isset($paymentsTypes[$paymentType][1]))
			{
				switch($paymentsTypes[$paymentType][1])
				{
					case 'payeer_wallet':
					
						$url = 'payeer/make-pay-off?id='.$id.'&type='.$type;
					
					break;
						
					default:
	
						$url = '';
						
					break;
				}
			}
		}

		if($url != '')
		{
			return $this->redirect(\Yii::$app->request->BaseUrl.'/'.$url);
		}
		else
		{
			return $this->redirect(['index']);
		}
	}
}
