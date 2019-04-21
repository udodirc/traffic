<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use common\models\Modules;
use yii\gii\Module;

/**
 * ModulesController implements the CRUD actions for Modules model.
 */
class ModulesListController extends Controller
{
	protected $permission;
	
	/**
     * @var \yii\gii\Module
    */
	public $module;
	
    /**
     * @var \yii\gii\Generator
    */
    public $generator;
    
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		return $this->render('index');
	}
	
	 /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new Modules();
		$post = Yii::$app->request->post();
		
		if(isset($post) && (!empty($post)))
		{
			/*echo '<pre>';
			print_r($post);
			echo '</pre>';*/
			
			/*if($model->createModuleTable($post))
			{
				
			}*/
			
			$id = 'module';
			$generator = $this->loadGenerator($id);
			
			/*echo '<pre>';
			print_r($generator);
			echo '</pre>';*/
		}
		
		return $this->render('create', [
			'model' => $model
        ]);
	}
	
	public function actionAddFieldByAjax()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$result = '';
		$post = Yii::$app->request->post();
		$id = (isset($post['id'])) ? intval($post['id']) : 0;
		$moduleID = (isset($post['module_id'])) ? $post['module_id'] : '';
		
		if($id > 0)
		{
			$result = $this->renderPartial('add_field', [
				'data' => array_filter(array_combine(array_keys(Modules::fieldsType()), array_column(Modules::fieldsType(), 'name'))),
				'id' => $id,
				'module_id' => $moduleID,
			]);
		}
		
		return $result;
	}
	
	public function actionGetFieldDataByAjax()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$result = '';
		$post = Yii::$app->request->post();
		$type = (isset($post['type'])) ? intval($post['type']) : 0;
		$id = (isset($post['id'])) ? intval($post['id']) : 0;
		
		if($type > 0 && $id > 0)
		{	
			$data = Modules::fieldsType();
			
			$result = $this->renderPartial('field_data', [
				'id' => $id,
				'type' => $type,
				'data' => (isset($data[$type]['field_data'])) ? $data[$type]['field_data'] : []
			]);
		}
		
		return $result;
	}
	
	/**
     * Loads the generator with the specified ID.
     * @param string $id the ID of the generator to be loaded.
     * @return \yii\gii\Generator the loaded generator
     * @throws NotFoundHttpException
     */
    protected function loadGenerator($id)
    {
		$this->module = new Module($id);
		$this->module->_basePath = '123';
        /*if (isset($this->module->generators[$id])) {
            $this->generator = $this->module->generators[$id];
            $this->generator->loadStickyAttributes();
            $this->generator->load($_POST);

            return $this->generator;
        } else {
            throw new NotFoundHttpException("Code generator not found: $id");
        }*/
    }
}
