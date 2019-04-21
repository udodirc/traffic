<?php

namespace backend\controllers;

use Yii;
use app\models\Permissions;
use app\models\PermissionsSearch;
use app\models\UserGroups;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PermissionsController implements the CRUD actions for Permissions model.
 */
class PermissionsController extends Controller
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
     * Lists all Permissions models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$searchModel = new PermissionsSearch(); 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); 
        $dataProvider->pagination->pageSize = Service::getPageSize();
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'group_list' => UserGroups::getGroupsList(),
            'permisions_list' => Permissions::getPermissionsList(),
        ]);
    }

    /**
     * Displays a single Permissions model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
		$model = Permissions::find()->with('groups')->where('permissions.id=:id', [':id' => $id])->one();
		
		return $this->render('view', [
            'model' => $model,
            'permisions_list' => unserialize($model->permissions),
        ]);
    }

    /**
     * Creates a new Permissions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new Permissions();
		
        if($model->load(Yii::$app->request->post())) 
        {	
			if($model->validate() && $model->save()) 
			{
				\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись добавлена!'));
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } 
        
		return $this->render('create', [
			'model' => $model,
			'errors' => $model->errors
       ]);
    }

    /**
     * Updates an existing Permissions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
		$model = Permissions::find()->with('groups')->where('permissions.id=:id', [':id' => $id])->one();
		Permissions::setPermissionsData($model, unserialize($model->permissions));
		
        if($model !== null) 
		{	
			if($model->load(Yii::$app->request->post())) 
			{	
				if($model->validate() && $model->save()) 
				{	
					\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
					return $this->redirect(['view', 'id' => $model->id]);
				}
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
     * Deletes an existing Permissions model.
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
     * Finds the Permissions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Permissions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Permissions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
}
