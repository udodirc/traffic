<?php
namespace common\modules\advertisement\controllers;

use common\modules\advertisement\repositories\TextAdvertBallsRepository;
use common\modules\advertisement\services\TextAdvertRequestService;
use common\modules\backoffice\models\Partners;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Service;
use common\models\StaticContent;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\modules\advertisement\models\TextAdvert;
use common\modules\advertisement\models\TextAdvertSearch;
use common\modules\advertisement\repositories\TextAdvertRepository;
use common\models\DbBase;

/**
 * FrontendTextAdvertController
 */
class FrontendTextAdvertController extends Controller
{
	public $layout = 'back_office';
	protected $user_id;
	protected $identity_id;
	private TextAdvertRepository $textAdvertRepository;
	private TextAdvertRequestService $textAdvertRequestService;

//	public function __construct
//	(
//		//TextAdvertRequestService $textAdvertRequestService
//	)
//	{
//		//$this->textAdvertRequestService = $textAdvertRequestService;
//		$this->textAdvertRepository = new TextAdvertRepository(new TextAdvert, new DbBase);
//	}
	
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
			'query' => TextAdvert::find()
			           ->where('status > 0')
			           ->andWhere('counter > 0')
			           ->orderBy('created_at DESC'),
			'pagination' => [
				'pageSize' => Service::getPageSize(),
			],
		]);
        
        $content = (!is_null(StaticContent::find()->where(['name'=>'text_advert']))) ? StaticContent::find()->where(['name'=>'text_advert'])->one() : null;
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
        $dataProvider = $searchModel->search($id, Yii::$app->request->post());
        $dataProvider->pagination->pageSize = Service::getPageSize();
        $content = (!is_null(StaticContent::find()->where(['name'=>'text_advert']))) ? StaticContent::find()->where(['name'=>'text_advert'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'Текстовая реклама партнера');
		
        return $this->render('partner-advert-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'content' => $content,
            'user' => true
        ]);
    }
    
    /**
     * Creates a new Text Advert model.
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
	        $model->counter = $model->balls;
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');

			if($model->save())
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Запись добавлена');
			}
			
            \Yii::$app->getSession()->setFlash($class, $msg);
            
			return $this->redirect(['partner-advert-list']);
        } 
        
        return $this->render('create', [
			'model' => $model,
			'partnerID' => $id
        ]);
    }

	/**
	 * Update a sText Advert model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;

		$model = $this->findModel($id);

		if($model->load(Yii::$app->request->post()) && $model->save())
		{
			return $this->redirect(['partner-advert-list']);
		}
		else
		{
			return $this->render('update', [
				'model' => $model,
				'id' => $id,
				'partnerID' => $partnerID,
			]);
		}
	}

	/**
	 * Deletes an existing Text Advert model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionDelete($id) {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;

		$model = $this->findModel($id);
		$model->deleted = 1;
		$model->save(false);

		return $this->redirect(['partner-advert-list']);
	}

	/**
	 * Redirect to link.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionLink($id) {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;

		$model = $this->findModel($id);
		$textAdvertBalls = (isset(\Yii::$app->params['text_advert_balls']))
			? Yii::$app->params['text_advert_balls']
			: 0;
		$textAdvertStructureBalls = (isset(\Yii::$app->params['text_advert_balls']))
			? Yii::$app->params['text_advert_structure_balls']
			: 0;

		if($textAdvertBalls > 0 && $textAdvertStructureBalls > 0)
		{
			$container = \Yii::$container;

			if($container->get(TextAdvertRequestService::class)->isTextAdvertShowed($id, $partnerID))
			{
				$advertUserID = $model->partner->id;
				$container->get(TextAdvertRepository::class)->setBalls(
					$partnerID,
					$advertUserID,
					$id,
					$textAdvertBalls,
					$textAdvertStructureBalls
				);
			}
		}

		return $this->redirect($model->link);
	}

	/**
	 * Finds the TextAdvert model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TextAdvert the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TextAdvert::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException(Yii::t('messages', 'Эта страница не существует!'));
		}
	}
}
