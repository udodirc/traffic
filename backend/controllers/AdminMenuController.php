<?php

namespace backend\controllers;

use Yii;
use app\models\Menu;
use app\models\AdminMenu;
use app\models\AdminMenuSearch;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * AdminMenuController implements the CRUD actions for AdminMenu model.
 */
class AdminMenuController extends Controller
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
     * Lists all AdminMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$searchModel = new AdminMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,     
            'menu_list' => ArrayHelper::map(AdminMenu::find()->where('parent_id = 0')->asArray()->all(), 'id', 'name'),
        ]);
    }

    /**
     * Displays a single AdminMenu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
		//Get model
		$model = new AdminMenu();
		
		//Get the menu data by ID 
		$model = $model->getMenuDataByID($id);
		
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
     * Creates a new AdminMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new AdminMenu();
		
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
     * Updates an existing AdminMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
        //Get the menu data by ID 
		$model = new AdminMenu();
		
		//Get the menu data by ID 
		$model = $model->getMenuDataByID($id);
		
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
     * Deletes an existing AdminMenu model.
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
     * Finds the AdminMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
}
