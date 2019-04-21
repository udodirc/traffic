<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use app\models\UserGroups;
use app\models\UserGroupsSearch;
use app\components\WebUser;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserGroupsController implements the CRUD actions for UserGroups model.
 */
class UserGroupsController extends Controller
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
    
    public function afterAction($action, $result)
    {
        if(!\Yii::$app->user->can($this->permission)) 
		{
			throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
		}
        
        return parent::afterAction($action, $result);
    }

    /**
     * Lists all UserGroups models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new UserGroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserGroups model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new UserGroups();

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {	
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись добавлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserGroups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserGroups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$this->permission = 'delete';
		
		$class = 'error';
		$msg = 'Ошибка!';
		$userID = User::find()->with('groups')->where('user.group_id=:id', [':id' => $id])->one();
		
		if($userID === NULL)
		{
			if($this->findModel($id)->delete())
			{
				$class = 'success';
				$msg = 'Запись удалена!';
			}
		}
		else
		{
			$msg = 'Удаление невозможно, в группе есть пользователь!';
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the UserGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserGroups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
        }
    }
}
