<?php

namespace common\modules\advertisement\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\modules\backoffice\models\forms\LoginForm;
use frontend\models\FeedbackForm;
use common\modules\advertisement\models\TextAdvert;
use common\modules\backoffice\models\Partners;
use common\models\StaticContent;

class FrontendPartnersController extends \yii\web\Controller
{
	public $layout = 'back_office';
	protected $user_id;
	protected $identity_id;
	
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ]
        ];
    }
    
    public function beforeAction($action) 
    {
        $this->enableCsrfValidation = (($action->id !== "set-math-captcha-by-ajax"));
        
        return parent::beforeAction($action);
    }
    
    /**
     * Check permission's rights
     * @return mixed
    */
    public function afterAction($action, $result)
    {
		$this->view->params['model'] = new LoginForm();
		$this->view->params['feedbackModel'] = new FeedbackForm();
		$this->view->params['contacts'] = (!is_null(StaticContent::find()->where(['name'=>'contacts']))) ? StaticContent::find()->where(['name'=>'contacts'])->one() : '';
		
		if($this->user_id > 0 && $this->identity_id > 0)
		{
			if($this->user_id != $this->identity_id) 
			{
				throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
			}
		}
		else
		{
			if(!$action->id == "set-math-captcha-by-ajax")
			{
				throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
			}
		}
		
        return parent::afterAction($action, $result);
    }
    
    public function actionIndex()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		$this->view->params['status'] = (!is_null(Partners::findOne(['id' => $id]))) ?  Partners::findOne(['id' => $id])->matrix : 0;
		$this->view->params['feedbackModel'] = new FeedbackForm();
		$this->view->params['contacts'] = (!is_null(StaticContent::find()->where(['name'=>'contacts']))) ? StaticContent::find()->where(['name'=>'contacts'])->one() : '';
		
		$dataProvider = new ActiveDataProvider([
			'query' => TextAdvert::find()->where(['partner_id'=>$id]),
			'pagination' => [
				'pageSize' => 10,
			],
		]);
		
        return $this->render('index', [
			'dataProvider' => $dataProvider,
			'partner_id' => $id
		]);
    }
    
    public function actionSetMathCaptchaByAjax()
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$result = '';
		
		if($partnerID > 0)
		{
			$model = new AddAdvertForm();
			
			$result = $this->renderPartial('partial/change_variants', [
				'model' => $model
			]);
		}
		
		return $result;
	}
	
	/**
     * Creates a new Advert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
		$this->user_id = $id;
		$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$model = new TextAdvert();
		
		if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись добавлена!'));
            return $this->redirect(['index']);
        } 
        
        return $this->render('create', [
            'model' => $model,
            'partner_id' => $id
        ]);
	}
	
	/**
     * Updates an existing Advert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		
		$model = $this->findModel($id);
        
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
                'partner_id' => $partnerID
            ]);
        }
    }
    
    /**
     * Deletes an existing Advert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$this->user_id = (!is_null($model)) ? $model->partner_id : 0;
		$this->identity_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
        $result = $model->delete();
		\Yii::$app->getSession()->setFlash(($result) ? 'success' : 'error', Yii::t('messages', ($result) ? 'Запись удалена!' : 'Ошибка!'));

        return $this->redirect(['index']);
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
        if (($model = TextAdvert::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
        }
    }
}
