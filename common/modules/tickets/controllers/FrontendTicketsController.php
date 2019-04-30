<?php

namespace common\modules\tickets\controllers;

use Yii;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\SearchTickets;
use common\modules\tickets\models\TicketsMessages;
use common\modules\tickets\models\forms\MessageForm;
use common\models\StaticContent;
use common\models\Service;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * FrontendTicketsController implements the CRUD actions for Tickets model.
 */
class FrontendTicketsController extends Controller
{
    public $layout = 'backoffice';
	public $theme = '';
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
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    public function beforeAction($event)
    {
		if(!Service::isActionAllowed('is_tickets_allowed'))
		{	
			throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
		}
		
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->view->params['tickets_list'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->all();
		$ticketsModel = new TicketsMessages();
		$this->view->params['tickets_count'] = $ticketsModel->getMessagesCountByPartnerID($id);
		$this->view->params['tickets_mesages_count'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->count();
		
		//Set theme
		$this->theme = (isset(\Yii::$app->params['defaultTheme'])) ? ('_'.\Yii::$app->params['defaultTheme']) : '';
		
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
     * Lists all Tickets models.
     * @return mixed
     */
    public function actionIndex()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
        $searchModel = new SearchTickets();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);
        $content = (!is_null(StaticContent::find()->where(['name'=>'tickets']))) ? StaticContent::find()->where(['name'=>'tickets'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'Запросы');
		
        return $this->render('index'.$this->theme, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'content' => $content,
            'id' => $id,
        ]);
    }

    /**
     * Displays a single Tickets model.
     * @param integer $id
     * @return mixed
     */
    public function actionTicket($id)
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		
		if(($ticketModel = Tickets::find()->with('partner')->where('id=:id', [':id' => $id])->one()) !== null) 
		{
			$this->view->params['title'] = $ticketModel->subject;
			$messageForm = new MessageForm();
			$model = new TicketsMessages();
			$dataProvider = new ActiveDataProvider([
				'query' => $model->getMessagesList($id),
				'pagination' => [
					'pageSize' => Service::getPageSize(),
				],
			]);
			
			return $this->render('view'.$this->theme, [
				'dataProvider' => $dataProvider,
				'ticketModel' => $ticketModel,
				'messageForm' => $messageForm,
			]);
        } 
        else 
        {
            throw new NotFoundHttpException(Yii::t('messages', 'Такая страница не сушествует!'));
        }
    }
    
    public function actionSendMessage()
    {	
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		$id = Yii::$app->request->get('id');
		
		$model = new MessageForm();
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
	
		if($model->load(Yii::$app->request->post())) 
		{
			if($model->sendMessage($id))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Ваше сообщение отправлено!');
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, $msg);
		
		return $this->redirect(\Yii::$app->request->BaseUrl.'/ticket/'.$id);
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
		
        $model = new Tickets();
		$this->view->params['title'] = Yii::t('form', 'Запрос');
		
        if($model->load(Yii::$app->request->post())) 
        {
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->createTicket($id))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Запрос создан');
			}
			
            \Yii::$app->getSession()->setFlash($class, $msg);
            
			return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
                'theme' => $this->theme,
            ]);
        }
    }

    /**
     * Updates an existing Tickets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tickets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tickets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tickets::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
