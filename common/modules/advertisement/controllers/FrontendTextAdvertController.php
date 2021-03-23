<?php
namespace common\modules\advertisement\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Service;
use common\models\StaticContent;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\modules\advertisement\models\TextAdvert;
use common\modules\advertisement\models\TextAdvertSearch;

/**
 * FrontendTextAdvertController
 */
class FrontendTextAdvertController extends Controller
{
	public $layout = 'back_office';
	protected $user_id;
	protected $identity_id;
	
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    public function beforeAction($event)
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$this->view->params['tickets_list'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->all();
		$ticketsModel = new TicketsMessages();
		$this->view->params['tickets_count'] = $ticketsModel->getMessagesCountByPartnerID($id);
		$this->view->params['tickets_mesages_count'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->count();
		
        return parent::beforeAction($event);
    }
    
    /**
     * Check permission's rights
     * @return mixed
    */
    public function afterAction($action, $result)
    {
		if($this->user_id > 0 && $this->identity_id > 0)
		{
			if($this->user_id != $this->identity_id) 
			{
				throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
			}
		}
		else
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
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
        $advertList = new ActiveDataProvider([
			'query' => TextAdvert::find()->where('status > 0')->orderBy('created_at DESC'),
			'pagination' => [
				'pageSize' => Service::getPageSize(),
			],
		]);
        
        $content = (!is_null(StaticContent::find()->where(['name'=>'text-advert']))) ? StaticContent::find()->where(['name'=>'text-advert'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'Текстовая реклама');
		
        return $this->render('index', [
            'advertList' => $advertList,
            'content' => $content
        ]);
    }
    
    public function actionPartnerAdvertList()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
        $searchModel = new TextAdvertSearch();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
        $content = (!is_null(StaticContent::find()->where(['name'=>'partner-text-advert']))) ? StaticContent::find()->where(['name'=>'partner-text-advert'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'Текстовая реклама партнера');
		
        return $this->render('partner-advert-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'content' => $content
        ]);
    }
    
    /**
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
        $model = new TextAdvert();
		$this->view->params['title'] = Yii::t('form', 'Добавить объявление');
		
        if($model->load(Yii::$app->request->post())) 
        {
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->save())
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Запись добавлена');
			}
			
            \Yii::$app->getSession()->setFlash($class, $msg);
            
			return $this->redirect(['index']);
        } 
        
        return $this->render('create', [
			'model' => $model,
        ]);
    }
}
