<?php
namespace common\modules\seo\controllers;

use Yii;
use common\modules\seo\models\Seo;
use common\modules\seo\models\forms\CountersForm;
use common\modules\seo\models\forms\IndexPageForm;
use common\modules\seo\models\Counters;
use common\models\Settings;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * BackendSeoController.
 */
class BackendSeoController extends Controller
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
     * Lists all Partners models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'create';
		
		$model = Seo::find()->where(['name'=>'index'])->one();
		
		if(!is_null($model))
		{
			if($model->load(Yii::$app->request->post()) && $model->save()) 
			{
				return $this->redirect(['index']);
			}
		}
		
		return $this->render('index', [
			'model' => $model
		]);
	}
	
	/**
     * Lists all Partners models.
     * @return mixed
     */
    public function actionCounters()
    {
		$this->permission = 'create';
		
		$countersForm = new CountersForm();
		$model = new Counters();
		$countersData = $model->getCountersData();
		
		if($countersForm->load(Yii::$app->request->post())) 
		{
			$countersForm->liveinternet = Html::encode(Yii::$app->request->post('CountersForm')['liveinternet']);
			$countersForm->yandex = Html::encode(Yii::$app->request->post('CountersForm')['yandex']);
			$countersForm->google = Yii::$app->request->post('CountersForm')['google'];
			
			if($model->updateCounters($countersForm))
			{
				\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
				
				return $this->redirect(['counters']);
			}
		}
		
		return $this->render('counters', [
			'model' => $countersForm,
			'countersData' => $countersData
		]);
	}
	
	/**
     * Display page for set index seo setttings.
     * @return mixed
     */
    public function actionIndexPage()
    {
		$this->permission = 'create';
		
		$seoData = Settings::find()->where(['name'=>'seo'])->asArray()->all();
		$model = new IndexPageForm();
		$update = false;
		
		if(!empty($seoData))
		{
			$seoData = (!empty($seoData)) ? ArrayHelper::map($seoData, 'index', 'value') : [];
			$model->title = (isset($seoData['title'])) ? $seoData['title'] : '';
			$model->meta_tags = (isset($seoData['meta_tags'])) ? $seoData['meta_tags'] : '';
			$model->meta_keywords = (isset($seoData['meta_keywords'])) ? $seoData['meta_keywords'] : '';
			$update = true;
		}
		
		if($model->load(Yii::$app->request->post())) 
		{
			$result = $model->save($update);
				
			if($result !== null)
			{
				\Yii::$app->getSession()->setFlash('success', Yii::t('messages', 'Запись обновлена!'));
					
				return $this->redirect(['index-page']);
			}
		}
			
		return $this->render('index-page', [
			'model' => $model
		]);
	}
}
