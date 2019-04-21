<?php
namespace common\modules\news\controllers;

use Yii;
use common\modules\news\models\News;
use common\modules\news\models\SearchNews;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * BackendNewsController implements the CRUD actions for News model.
 */
class BackendNewsController extends Controller
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
				'url' => Url::base().'/uploads/content/news', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/news/', // Or absolute path to directory where files are stored.
				'type' => '0',
			],
			'uploads' => [
				'class' => 'vova07\imperavi\actions\UploadAction',
				'url' => Url::base().'/uploads/content/news', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/news' // Or absolute path to directory where files are stored.
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new SearchNews();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionNews($id)
    {
		$this->permission = 'view';
		
        return $this->render('news', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
        return $this->render('news', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new News();

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
     * Updates an existing News model.
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
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$this->permission = 'delete';
		
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages', 'Эта страница не существует!'));
        }
    }
}
