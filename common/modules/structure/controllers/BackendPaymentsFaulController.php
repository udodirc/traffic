<?php
namespace common\modules\structure\controllers;

use Yii;
use common\modules\structure\models\Payment;
use common\modules\structure\models\PaymentsFaul;
use common\modules\structure\models\PaymentsFaulSearch;
use common\modules\structure\models\MatricesSettings;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\forms\MessageForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * BackendPaymentsFaulController implements the CRUD actions for PaymentsFaul model.
 */
class BackendPaymentsFaulController extends Controller
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
     * Lists all PaymentsFaul models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new PaymentsFaulSearch();
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
		
		$model = new PaymentsFaul();
		$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
		
        return $this->render('view', [
            'model' => $model->getFaulBydID($id),
            'paymentTypes' => Yii::$app->params['payments_types'],
            'structuresList' => $structuresList,
        ]);
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
	
	/**
     * Pay item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	public function actionPay($id)
    {
		$this->permission = 'update';
		
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		$model = $this->findModel($id);
		$matricesSettings = Matrix::getMatricesSettings($model->structure_number, $model->matrix_number);
		
		if(isset($matricesSettings['account_type']) && (Payment::isPaymentAllowed()))
		{
			if($matricesSettings['account_type'] = '2')
			{
				if((isset(Yii::$app->params['payments_types'])))
				{
					$paymentsFaulModel = new PaymentsFaul();
					
					if($paymentsFaulModel->makePayment($model, $matricesSettings))
					{
						$model->paid = 1;
						
						if($model->save(false))
						{	
							$class = 'success';
							$msg = Yii::t('messages', 'Выплата выполнена!');
						}
					}
				}
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
		
		return $this->redirect(['index']);
	}
	
	/**
     * Pay all items in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
	public function actionTotalPayment()
    {
		$this->permission = 'update';
		
		$model = new PaymentsFaul();
		$result = $model->makeTotalPaymentInFauls();
		
		if($result[0])
		{	
			$class = 'success';
			$msg = Yii::t('messages', 'Выплата выполнена!');
		}
		else
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Количество невыполненных транзакций!').' - '.$result[1];
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
		
		return $this->redirect(['index']);
	}
	
	/**
     * Send message to partners.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
	public function actionMessage()
    {
		$this->permission = 'update';
		
		$model = new MessageForm();
		
		if($model->load(Yii::$app->request->post()))
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->send())
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Партнер активирован!');
			}
				
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
			
			return $this->redirect(['index']);
		}
			
		return $this->render('message', [
			'model' => $model
		]);
	}
	
	public function actionCompareTransactions()
    {
		$this->permission = 'update';
		
		PaymentsFaul::compareTransactions();
	}
	
	/**
     * Deletes an existing Payment Faul model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$this->permission = 'delete';
		
        $result = $this->findModel($id)->delete();
		\Yii::$app->getSession()->setFlash(($result) ? 'success' : 'error', Yii::t('messages', ($result) ? 'Запись удалена!' : 'Ошибка!'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the PaymentsFaul model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentsFaul the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentsFaul::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
