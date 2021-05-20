<?php

namespace common\modules\tickets\controllers;

use Yii;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\SearchTickets;
use common\modules\tickets\models\TicketsMessages;
use common\modules\tickets\models\forms\MessageForm;
use common\modules\tickets\models\forms\MailingForm;
use common\models\Service;
use common\components\geo\IsoHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;


/**
 * BackendTicketsController implements the CRUD actions for Tickets model.
 */
class BackendTicketsController extends Controller
{
	protected $permission;
	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->permission = 'view';
		
		$searchModel = new SearchTickets();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	/**
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->permission = 'create';
		
        $model = new Tickets();
		
        if($model->load(Yii::$app->request->post())) 
        {
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->createTicket())
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
            ]);
        }
    }
	
	/**
     * Displays a single Tickets model.
     * @param integer $id
     * @return mixed
     */
    public function actionTicket($id)
    {
		$this->permission = 'view';
		
		if(($ticketModel = Tickets::find()->with('partner')->where('id=:id', [':id' => $id])->one()) !== null) 
		{
			$messageForm = new MessageForm();
			$messageForm->scenario = ('backend');
			$model = new TicketsMessages();
			$dataProvider = new ActiveDataProvider([
				'query' => $model->getMessagesList($id),
				'pagination' => [
					'pageSize' => Service::getPageSize(),
				],
			]);
			
			return $this->render('view', [
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
    
    public function actionSendMessage($id)
    {	
		$this->permission = 'create';
		
		$userID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$model = new MessageForm();
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
			
		if($model->load(Yii::$app->request->post())) 
		{	
			if($model->sendMessage($id, 2, $userID))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Ваше сообщение отправлено!');
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, $msg);
		
		return $this->redirect(\Yii::$app->request->BaseUrl.'/tickets/backend-tickets/ticket/'.$id);
    }
    
    public function actionMailing()
    {	
		$this->permission = 'create';
		
		$model = new MailingForm();
		$countryList = IsoHelper::getIsoList('en');
		
		if($model->load(Yii::$app->request->post()))
		{
			$class = 'error';
			$msg = Yii::t('messages', 'Ошибка!');
			
			if($model->send($model))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Файл обновлен!');
			}
				
			\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
				
			return $this->redirect(['index']);
		}
		
		return $this->render('mailing', [
			'model' => $model,
			'country_list' => $countryList,
			'statuses_list' => (isset(Yii::$app->params['update_emails_statuses_list'])) ? Yii::$app->params['update_emails_statuses_list'] : [],
		]);
	}
    
    /**
     * Deletes an existing Ticket Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteMessage($id, $ticket_id)
    {
        $this->findMessageModel($id)->delete();

        return $this->redirect(\Yii::$app->request->BaseUrl.'/tickets/backend-tickets/ticket/'.$ticket_id);
    }
    
    /**
     * Deletes an existing Ticket Message model.
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

    /**
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tickets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMessageModel($id)
    {
        if (($model = TicketsMessages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
