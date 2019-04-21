<?php

namespace backend\controllers;

use Yii;
use common\models\Content;
use backend\models\ContentSearch;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends Controller
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
            ]
        ];
    }
    
    public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'images-get' => [
				'class' => 'vova07\imperavi\actions\GetAction',
				'url' => Url::base().'/uploads/content/content', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/content/', // Or absolute path to directory where files are stored.
				'type' => '0',
			],
			'uploads' => [
				'class' => 'vova07\imperavi\actions\UploadAction',
				'url' => Url::base().'/uploads/content/content', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/content' // Or absolute path to directory where files are stored.
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
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new ContentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'controlerList' => Service::getControllersList('content_controllers')
        ]);
    }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
        return $this->render('view', [
            'model' => $this->findModel($id),
            'controler_list' => Service::getControllersList('content_controllers')
        ]);
    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new Content();
        $model->style = isset(Yii::$app->request->post()['Content']['style']) ? Yii::$app->request->post()['Content']['style'] : '';
        
        if($model->load(Yii::$app->request->post()) && $model->save(false)) 
        {	
			\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись добавлена!'));
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
		//Set model
		$model = Content::find()->with('menu')->where('content.id=:id', [':id' => $id])->one();
		
		if($model !== null) 
		{	
			if($model->load(Yii::$app->request->post())) 
			{	
				$model->style = isset(Yii::$app->request->post()['Content']['style']) ? Yii::$app->request->post()['Content']['style'] : '';
				
				if($model->save(false))
				{
					\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
					return $this->redirect(['view', 'id' => $id]);
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
     * Deletes an existing Content model.
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
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Content::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
}
