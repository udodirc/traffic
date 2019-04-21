<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Menu;
use app\models\AdminMenu;
use app\models\AdminMenuSearch;
use app\models\forms\PaymentsSettingsForm;
use app\models\forms\ModulesSettingsForm;
use common\models\Settings;

class SettingsController extends \yii\web\Controller
{
	public $controllerID = 0;
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
     * Lists all AdminMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'update';
		
		$menu = new Menu();
        $searchModel = new AdminMenuSearch();
        
        $menuList = $menu->getMenuListByIDIndex(true);
        $dataProvider = new ActiveDataProvider([
			'query' => \app\models\AdminMenu::find(),
			'pagination' => [
				'pageSize' => 2,
			],
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,     
            'menu_list' => $menuList,
        ]);
    }
    
    public function actionPaymentsSettings()
    {
		$this->permission = 'update';
		
		$model = new PaymentsSettingsForm();
		$isActivationAllowed = Settings::find()->select(['value'])->where(['name'=>'is_activation_allowed'])->asArray()->one();
		$isPaymentAllowed = Settings::find()->select(['value'])->where(['name'=>'is_payment_allowed'])->asArray()->one();
		
		if($model->load(Yii::$app->request->post())) 
		{	
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->setSettings()) 
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Настройки изменены!');
			}
				
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
						
			return $this->redirect(['payments-settings']);
		}

		return $this->render('payments-settings', [
			'model' => $model,
			'isActivationAllowed' => (isset($isActivationAllowed['value']) ? $isActivationAllowed['value'] : false),
			'isPaymentAllowed' => (isset($isPaymentAllowed['value']) ? $isPaymentAllowed['value'] : false)
		]);
	}
	
	public function actionModulesSettings()
    {
		$this->permission = 'update';
		
		$model = new ModulesSettingsForm();
		$isFeedbacksAllowed = Settings::find()->select(['value'])->where(['name'=>'is_feedbacks_allowed'])->asArray()->one();
		$isTicketsAllowed = Settings::find()->select(['value'])->where(['name'=>'is_tickets_allowed'])->asArray()->one();
		
		if($model->load(Yii::$app->request->post())) 
		{	
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->setSettings()) 
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Настройки изменены!');
			}
				
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
						
			return $this->redirect(['modules-settings']);
		}

		return $this->render('modules-settings', [
			'model' => $model,
			'isFeedbacksAllowed' => (isset($isFeedbacksAllowed['value']) ? $isFeedbacksAllowed['value'] : false),
			'isTicketsAllowed' => (isset($isTicketsAllowed['value']) ? $isTicketsAllowed['value'] : false)
		]);
	}
}
