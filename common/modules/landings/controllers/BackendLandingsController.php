<?php

namespace common\modules\landings\controllers;

use Yii;
use common\modules\landings\models\Landings;
use common\modules\landings\models\SearchLandings;
use common\modules\landings\models\forms\EditFileForm;
use common\components\FileHelper;
use common\modules\uploads\models\Files;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackendLandingsController implements the CRUD actions for Landings model.
 */
class BackendLandingsController extends Controller
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
     * Lists all Landings models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
        $searchModel = new SearchLandings();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Landings model.
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
     * Creates a new Landings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new Landings();
        $url = (isset(Yii::$app->params['landings_dir'])) ? Yii::getAlias('@slider').DIRECTORY_SEPARATOR : '';
		$category = 'landings';
		
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect('index');
        } 
        else 
        {
			return $this->render('create', [
                'model' => $model,
                'url' => $url,
                'category' => $category,
            ]);
        }
    }

    /**
     * Updates an existing Landings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->permission = 'update';
		
        $model = $this->findModel($id);
		$url = (isset(Yii::$app->params['landings_dir'])) ? Yii::getAlias('@slider').DIRECTORY_SEPARATOR : '';
		$category = 'landings';
		
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect('index');
        } 
        else 
        {
			$assetsDir = Landings::getSourcePath('assets_path', $id);
			$imagesDir = Landings::getSourcePath('images_path', $id);
			$filesDir = \Yii::getAlias('@'.Yii::$app->params['upload_dir'][$category]['alias']).DIRECTORY_SEPARATOR;
			$imagesFiles = [];
			$assetsFiles = [];
			
			if(is_dir($imagesDir)) 
			{
				$imagesFiles = FileHelper::findFiles($imagesDir, ['dir_structure'=>true]);
			}
			
			if(is_dir($assetsDir)) 
			{
				$assetsFiles = FileHelper::findFiles($assetsDir, ['dir_structure'=>true]);
			}
			
            return $this->render('update', [
                'model' => $model,
                'url' => $url,
                'category' => $category,
                'id' => $id,
                'assetsFiles' => $assetsFiles,
                'imagesFiles' => $imagesFiles,
                'filesDir' => $filesDir,
            ]);
        }
    }

    /**
     * Deletes an existing Landings model.
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
    
    public function actionEditFile($id, $file)
    {
		$this->permission = 'update';
		
		$category = 'landings';
		$model = new EditFileForm;
		$alias = (isset(Yii::$app->params['upload_dir'][$category]['alias'])) ? Yii::$app->params['upload_dir'][$category]['alias'] : '';
		
		if($alias != '')
		{		
			if($model->load(Yii::$app->request->post())) 
			{	
				$file = \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$file;
				
				if($model->update($file))
				{
					return $this->redirect(['update', 'id'=>$id]);
				}
			} 
			else 
			{		
				$text = Files::readTextFile(\Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$file);
				
				return $this->render('file_edit', [
					'model' => $model,
					'text' => $text,
					'category' => $category,
				]);
			}
		}
		else
		{
			throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы нет!'));
		}
	}

    /**
     * Finds the Landings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Landings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Landings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
