<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use app\components\WebUser;
use app\models\UsersSearch;
use app\models\UserGroups;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
	protected $permission;
	
    public function behaviors()
    {
        return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => 'updated_at',
				'value' => new Expression('NOW()')
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {	
		$this->permission = 'view';
		
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'group_list' => ArrayHelper::map(UserGroups::find()->asArray()->all(), 'id', 'name')
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		$model = User::find()->with('groups')->where('user.id=:id', [':id' => $id])->one();
		
        return $this->render('view', [
            'model' => $model,
            'profile' => (strpos($_SERVER['REQUEST_URI'], 'profile') !== false) ? true : false,
        ]);
    }
    
    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new User(['scenario' => 'register']);
		
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись добавлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
                'profile' => 1
            ]);
        }
    }
    
    /**
     * Activate item in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $publish
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
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $profile)
    {
		$this->permission = 'update';
		
        $model = $this->findModel($id);
        $model->scenario = ('update');
	
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
                'profile' => $profile
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
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
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
        }
    }
}
