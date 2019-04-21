<?php

namespace common\modules\structure\controllers;

use Yii;
use common\modules\structure\models\Withdrawal;
use common\modules\structure\models\SearchWithdrawal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackendWithdrawalController implements the CRUD actions for Withdrawal model.
 */
class BackendWithdrawalController extends Controller
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
        $searchModel = new SearchWithdrawal();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		$withdrawalStatuses = (isset(Yii::$app->params['withdrawal_statuses'])) ? Yii::$app->params['withdrawal_statuses'] : [];
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'paymentsTypes' => $paymentsTypes,
			'withdrawalStatuses' => $withdrawalStatuses,
        ]);
    }

    /**
     * Displays a single Withdrawal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Withdrawal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Withdrawal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Withdrawal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Publish withdrawal item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStatus($id, $status)
    {
		$this->permission = 'update';
		
		$model = Withdrawal::findOne($id);
		
		if($model !== null)
		{
			$model->status = ($status > 1) ? Withdrawal::STATUS_REJECT : Withdrawal::STATUS_CONFIRM;
			$model->save(false);  // equivalent to $model->update();
		}
		
		return $this->redirect(['index']);
	}
	
	 /**
     * Reject withdrawal item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionReject($id)
    {
		$this->permission = 'update';
		
		$model = Withdrawal::findOne($id);
		
		if($model !== null)
		{
			$model->status = Withdrawal::STATUS_REJECT;
			$model->save(false);  // equivalent to $model->update();
		}
		
		return $this->redirect(['index']);
	}
	
	/**
     * Confirm withdrawal item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionConfirm($id)
    {
		$this->permission = 'update';
		
		$data = Withdrawal::findOne($id);
		
		if($data !== null)
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			$model = new Withdrawal;
			
			if($model->makeMoneyWithdrawal($id, Withdrawal::STATUS_CONFIRM, $data->partner_id, $data->amount))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Запрос на вывод совершен!');
			}	
			
			\Yii::$app->getSession()->setFlash($class, $msg);
				
			return $this->redirect(['index', 'id'=>$id]);
		}
		
		return $this->redirect(['index']);
	}

    /**
     * Deletes an existing Withdrawal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Withdrawal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Withdrawal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Withdrawal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
