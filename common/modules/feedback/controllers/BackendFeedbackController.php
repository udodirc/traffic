<?php

namespace common\modules\feedback\controllers;

use Yii;
use yii\helpers\Url;
use common\modules\feedback\models\Feedback;
use common\modules\feedback\models\SearchFeedback;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackendFeedbackController implements the CRUD actions for Feedback model.
 */
class BackendFeedbackController extends Controller
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
     * Lists all Feedback models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new SearchFeedback();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Feedback model.
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
     * Creates a new Feedback model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new Feedback();

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Feedback model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Feedback model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$this->permission = 'update';
		
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Feedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feedback::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
