<?php

namespace backend\controllers;

use Yii;
use common\models\StaticContent;
use app\models\StaticContentSearch;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * StaticContentController implements the CRUD actions for StaticContent model.
 */
class StaticContentController extends Controller
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
				'url' => Url::base().'/uploads/content/static-content', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/static-content/', // Or absolute path to directory where files are stored.
				'type' => '0',
			],
			'uploads' => [
				'class' => 'vova07\imperavi\actions\UploadAction',
				'url' => Url::base().'/uploads/content/static-content', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/static-content' // Or absolute path to directory where files are stored.
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
     * Lists all StaticContent models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new StaticContentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaticContent model.
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
     * Creates a new StaticContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new StaticContent();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
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
     * Updates an existing StaticContent model.
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
     * Activation of content in base.
     * If publish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id, $status)
    {
		$this->permission = 'update';
		
		$model = $this->findModel($id);
		$model->status = ($status > 0) ? 0 : 1;
		$model->save();  // equivalent to $model->update();
		
		return $this->redirect(['index']);
	}

    /**
     * Deletes an existing StaticContent model.
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
     * Finds the StaticContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaticContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaticContent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
