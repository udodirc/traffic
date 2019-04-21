<?php

namespace backend\controllers;

use Yii;
use common\models\Menu;
use app\models\MenuCategories;
use app\models\MenuSearch;
use common\models\Service;
use common\components\geo\IsoHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use common\components\DropDownListHelper;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$searchModel = new MenuSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'menu_list' => ArrayHelper::map(Menu::find()->asArray()->all(), 'id', 'name'),
            'categories_list' => ArrayHelper::map(MenuCategories::find()->asArray()->all(), 'id', 'name'),
            'controler_list' => Service::getControllersList('menu_controllers'),
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->permission = 'view';
		
		//Get model
		$model = new Menu();
		
        //Get the menu data by ID 
		$model = $model->getMenuDataByID($id);
		
		if($model !== null) 
		{
			 return $this->render('view', [
				'model' => $model
			]);
		} 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
    
    public function actionGetMenuDropDownListByAjax()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$post = Yii::$app->request->post();
		$controllerID = (isset($post['controller_id'])) ? intval($post['controller_id']) : 0;
		$id = (isset($post['id'])) ? intval($post['id']) : 0;
		$submenu = (isset($post['submenu'])) ? filter_var($post['submenu'], FILTER_VALIDATE_BOOLEAN) : false;
		$result = '';
		
		if($id > 0 && $controllerID > 0)
		{
			$id = ($submenu || $controllerID == 7) ? $id : 0;
			$params = [
				6=>['model'=>'Menu', 'model_dir'=>'common', 'select_params'=>['id', 'name'], 'where_params'=>'category_id = :id  AND parent_id = 0', 'selector_id'=>'parent_id', 'menu_name'=>'Выберите меню', 'menu_id'=>'menu_list_selector'],
				7=>['model'=>'Content', 'model_dir'=>'common', 'select_params'=>['id', 'title'], 'where_params'=>'controller_id = :id', 'selector_id'=>'content_id', 'menu_name'=>'Выберите данные', 'menu_id'=>'submenu_controllers-list-selector'],
				8=>['model'=>'StaticContent', 'model_dir'=>'common', 'select_params'=>['id', 'name'], 'where_params'=>'', 'selector_id'=>'content_id', 'menu_name'=>'Выберите данные', 'menu_id'=>'submenu_controllers-list-selector'],
			];
			
			if(isset($params[$controllerID]))
			{
				$result = DropDownListHelper::getHtmlDropDownList($params[$controllerID]['model'], $params[$controllerID]['model_dir'], $params[$controllerID]['select_params'], [$params[$controllerID]['where_params'], [':id'=>$id]], 'Menu['.$params[$controllerID]['selector_id'].']', [
					'prompt'=>Yii::t('form', $params[$controllerID]['menu_name']),
					'style'=> 'width:300px;',
					'class'=>'form-control',
					'id' => $params[$controllerID]['menu_id']
				]);
			}
		}
		
		return $result;
    }
    
    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu(['scenario' => 'create']);
        $model->controller_submenu_id = (isset(Yii::$app->request->post()['Menu']['controller_submenu_id'])) ? intval(Yii::$app->request->post()['Menu']['controller_submenu_id']) : 0;
        
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {	
			return $this->redirect(['index']);
        } 
        else 
        {
			$statusList = (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [];
			$countryList = IsoHelper::getIsoList('en');
			 
            return $this->render('create', [
                'model' => $model,
				'id' => 0,
				'status_list' => $statusList,
				'country_list' => $countryList,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		//Set model
		$menu = new Menu();
		
		//Get the menu data by ID 
		$model = $menu->getMenuDataByID($id);
		$model->scenario = ('update');
	
		if($model !== null) 
		{	
			if($model->load(Yii::$app->request->post()) && $model->save()) 
			{	
				return $this->redirect(['view', 'id' => $model->id]);
			} 
			else 
			{
				$statusList = (isset(Yii::$app->params['reserve_statuses'])) ? Yii::$app->params['reserve_statuses'] : [];
				
				return $this->render('update', [
					'model' => $model,
					'id' => $id,
					'status_list' => $statusList,
				]);
			}
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
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
		$model->save();  // equivalent to $model->update();
		
		return $this->redirect(['index']);
	}
	
    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		$menuData = Menu::find()->where('parent_id=:id', [':id' => $id])->one();
		
		if($menuData === NULL)
		{
			if($this->findModel($id)->delete())
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Запись удалена!');
			}
		}
		else
		{
			$msg = Yii::t('messages', 'Удаление невозможно, в меню есть другое меню!');
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
        }
    }
}
