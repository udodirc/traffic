<?php

namespace common\modules\advertisement\controllers;

use Yii;
use common\modules\advertisement\models\SponsorAdvert;
use common\modules\advertisement\models\SearchBackendSponsorAdvert;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackendSponsorAdvertController implements the CRUD actions for SponsorAdvert model.
 */
class BackendSponsorAdvertController extends Controller
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
    
    public function beforeAction($action) 
    {
        $this->enableCsrfValidation = (($action->id !== "create"));
        
        return parent::beforeAction($action);
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
     * Lists all SponsorAdvert models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$searchModel = new SearchBackendSponsorAdvert();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
		$this->view->params['title'] = Yii::t('form', 'Спонсорская реклама');
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SponsorAdvert model.
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
     * Creates a new SponsorAdvert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new SponsorAdvert();

		if($model->load(Yii::$app->request->post()) && $model->save(false)) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
			$category = 'sponsor_advert';
			$url = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::getAlias('@banners_advert').DIRECTORY_SEPARATOR : '';
			$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
			
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
				'url' => $url,
				'thumbnail' => $thumbnail
            ]);
        }
    }

    /**
     * Updates an existing SponsorAdvert model.
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
			$category = 'sponsor_advert';
			$url = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::getAlias('@banners_advert').DIRECTORY_SEPARATOR : '';
			$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
			
			return $this->render('update', [
                'model' => $model,
                'category' => $category,
				'url' => $url,
				'thumbnail' => $thumbnail,
				'id' => $id
            ]);
        }
    }

    /**
     * Deletes an existing SponsorAdvert model.
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
     * Finds the SponsorAdvert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SponsorAdvert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SponsorAdvert::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
