<?php
namespace common\modules\feedback\controllers;

use Yii;
use common\modules\feedback\models\Feedback;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\models\StaticContent;
use common\models\Service;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * BackofficeFeedbackControllerController implements the CRUD actions.
 */
class BackofficeFeedbackController extends Controller
{
	public $layout = 'back_office';
	protected $user_id;
	protected $identity_id;
	
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get', 'post'],
                ],
            ],
        ];
    }
    
    public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'images-get' => [
				'class' => 'vova07\imperavi\actions\GetAction',
				'url' => Url::base().'/uploads/feedback', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/feedback/', // Or absolute path to directory where files are stored.
				'type' => '0',
			],
			'uploads' => [
				'class' => 'vova07\imperavi\actions\UploadAction',
				'url' => Url::base().'/uploads/content/feedback', // Directory URL address, where files are stored.
				'path' => '@backend_uploads/uploads/feedback' // Or absolute path to directory where files are stored.
			],
		];
	}
    
    public function beforeAction($event)
    {
		if(!Service::isActionAllowed('is_feedbacks_allowed'))
		{	
			throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
		}
		
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$this->view->params['tickets_list'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->all();
		$ticketsModel = new TicketsMessages();
		$this->view->params['tickets_count'] = $ticketsModel->getMessagesCountByPartnerID($id);
		$this->view->params['tickets_mesages_count'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->count();
		$this->enableCsrfValidation = false;
		
		/*if($this->action->id == 'create') 
		{
            Yii::$app->controller->enableCsrfValidation = false;
        }*/
		 
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
     * Renders the index view for the module
     * @return string
    */
    public function actionIndex()
    {
		//throw new NotFoundHttpException(Yii::t('messages', 'Такой страницы не существует!'));
		
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		//$this->enableCsrfValidation = false;
		
		$content = (!is_null(StaticContent::find()->where(['name'=>'feedback']))) ? StaticContent::find()->where(['name'=>'feedback'])->one() : null;
		$feedbackList = new ActiveDataProvider([
			'query' => Feedback::find()->orderBy('created_at DESC'),
			'pagination' => [
				'pageSize' => Service::getPageSize(),
			],
		]);
		$this->view->params['title'] = Yii::t('form', 'Отзывы');
		
        return $this->render('index', [
            'feedbackList'=>$feedbackList,
            'content'=>$content
        ]);
    }
    
    /**
     * Creates a new Feedback model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$model = new Feedback();
		$this->view->params['title'] = Yii::t('form', 'Добавление отзыва');
		
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {	
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
     * Updates an existing Feedback model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
	public function actionUpdate($id)
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		
        $model = $this->findModel($id);
		$this->view->params['title'] = Yii::t('form', 'Редактирование отзыва');
		
        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing Feedback model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		
		$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Finds the Feedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feedback::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

