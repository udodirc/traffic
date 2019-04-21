<?php
namespace common\modules\advertisement\controllers;

use Yii;
use common\modules\advertisement\models\SponsorAdvert;
use common\modules\advertisement\models\SearchFrontSponsorAdvert;
use common\models\Service;
use common\models\StaticContent;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\modules\backoffice\models\Partners;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FrontendSponsorAdvertController implements the CRUD actions for SponsorAdvert model.
 */
class FrontendSponsorAdvertController extends Controller
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
    
    public function beforeAction($action)
    {
		$this->enableCsrfValidation = (($action->id !== "create"));
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		
		$this->view->params['tickets_list'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->all();
		$ticketsModel = new TicketsMessages();
		$this->view->params['tickets_count'] = $ticketsModel->getMessagesCountByPartnerID($id);
		$this->view->params['tickets_mesages_count'] = Tickets::find()->where('partner_id=:id AND status=:status', [':id' => $id, ':status' => Tickets::STATUS_ADMIN_ANSWER])->count();
		
        return parent::beforeAction($action);
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
		
        $searchModel = new SearchFrontSponsorAdvert();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = Service::getPageSize();
        $content = (!is_null(StaticContent::find()->where(['name'=>'sponsor-advert']))) ? StaticContent::find()->where(['name'=>'sponsor-advert'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'Спонсорская реклама');
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'content' => $content
        ]);
    }

    /**
     * Displays a single SponsorAdvert model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SponsorAdvert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
        $model = new SponsorAdvert();

		if($model->load(Yii::$app->request->post()) && $model->save(false)) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
			$category = 'sponsor_advert';
			$url = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::getAlias('@sponsor_advert').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR : '';
			$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
		
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
				'url' => $url,
				'thumbnail' => $thumbnail
            ]);
        }
    }

    /**
     * Updates an existing SponsorAdvert model.
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

        if($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['index']);
        } 
        else 
        {
			$category = 'sponsor_advert';
			$url = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::getAlias('@sponsor_advert').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR : '';
			$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
		
			return $this->render('update', [
                'model' => $model,
                'category' => $category,
				'url' => $url,
				'thumbnail' => $thumbnail,
				'id' => $id
            ]);
        }
    }

    /**
     * Deletes an existing SponsorAdvert model.
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
     * Lists all SponsorAdvert models.
     * @return mixed
     */
    public function actionSponsorAdvert()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$partnerModel = Partners::findOne($id);
		
		if($partnerModel !== null) 
        {
			$dataProvider = new ActiveDataProvider([
				'query' => SponsorAdvert::find()->where(['partner_id' => $partnerModel->sponsor_id]),
				'pagination' => [
					'pageSize' => Service::getPageSize(),
				],
			]);
			$content = (!is_null(StaticContent::find()->where(['name'=>'sponsors-advert']))) ? StaticContent::find()->where(['name'=>'sponsors-advert'])->one() : null;
			$this->view->params['title'] = Yii::t('form', 'Реклама спонсора');
			
			return $this->render('sponsors-advert', [
				'dataProvider' => $dataProvider,
				'content' => $content
			]);
        }
		else 
        {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
    
     /**
     * Lists all Advert models.
     * @return mixed
     */
    public function actionAllAdvert()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		$dataProvider = new ActiveDataProvider([
			'query' => SponsorAdvert::find(),
			'pagination' => [
				'pageSize' => Service::getPageSize(),
			],
		]);
		$content = (!is_null(StaticContent::find()->where(['name'=>'all-advert']))) ? StaticContent::find()->where(['name'=>'all-advert'])->one() : null;
		$this->view->params['title'] = Yii::t('form', 'Вся реклама');
			
		return $this->render('sponsors-advert', [
			'dataProvider' => $dataProvider,
			'content' => $content
		]);
    }

    /**
     * Finds the SponsorAdvert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SponsorAdvert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SponsorAdvert::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages','Страница не найдена!'));
        }
    }
}
