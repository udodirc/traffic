<?php

namespace backend\controllers;

use Yii;
use app\models\Pagination;
use app\models\PaginationSearch;
use app\models\AdminMenu;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PaginationController implements the CRUD actions for Pagination model.
 */
class PaginationController extends Controller
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
     * Lists all Pagination models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$searchModel = new PaginationSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'menu_list' => ArrayHelper::map(AdminMenu::find()->asArray()->all(), 'id', 'name'),
        ]);
    }

    /**
     * Displays a single Pagination model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
		//Set model
		$model = new Pagination();
		
		//Get the data by ID 
		$model = $model->getPaginationDataByID($id);
		
		if($model !== null) 
		{
			return $this->render('view', [
				'model' => $model
			]);
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }

    /**
     * Creates a new Pagination model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new Pagination();
        
		if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись добавлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
       
        return $this->render('create', [
			'model' => $model
        ]);
    }

    /**
     * Updates an existing Pagination model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
        //Set model
		$model = new Pagination();
		
		//Get the menu data by ID 
		$model = $model->getPaginationDataByID($id);
		
		if($model !== null) 
		{
			if($model->load(Yii::$app->request->post()) && $model->save()) 
			{	
				\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
				return $this->redirect(['view', 'id' => $model->id]);
			} 
			else 
			{
				return $this->render('update', [
					'model' => $model,
					'id' => $id
				]);
			}
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }

    /**
     * Deletes an existing Pagination model.
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
     * Finds the Pagination model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pagination the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pagination::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
}
