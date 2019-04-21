<?php

namespace common\modules\faq\controllers;

use Yii;
use common\modules\faq\models\Faq;
use common\modules\faq\models\SearchFaq;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * BackendFaqController implements the CRUD actions for Faq model.
 */
class BackendFaqController extends Controller
{
	public $layout = 'backend';
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
    
    public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'images-get' => [
				'class' => 'vova07\imperavi\actions\GetAction',
				'url' => Url::base().'/uploads/content/faq', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/faq/', // Or absolute path to directory where files are stored.
				'type' => '0',
			],
			'uploads' => [
				'class' => 'vova07\imperavi\actions\UploadAction',
				'url' => Url::base().'/uploads/content/faq', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/content/faq' // Or absolute path to directory where files are stored.
			],
		];
	}

    /**
     * Lists all Faq models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new SearchFaq();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$typesList = (isset(Yii::$app->params['faq_type'])) ? Yii::$app->params['faq_type'] : [];
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'typesList' => $typesList,
        ]);
    }

    /**
     * Displays a single Faq model.
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
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new Faq();

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        }
        else 
        {
			$typesList = (isset(Yii::$app->params['faq_type'])) ? Yii::$app->params['faq_type'] : [];
			
            return $this->render('create', [
                'model' => $model,
                'typesList' => $typesList,
            ]);
        }
    }

    /**
     * Updates an existing Faq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'typesList' => [],
            ]);
        }
    }

    /**
     * Deletes an existing Faq model.
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
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
