<?php

namespace common\modules\news\controllers;

use Yii;
use common\modules\news\models\News;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\models\StaticContent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FrontendNewsController implements the CRUD actions for News model.
 */
class FrontendNewsController extends Controller
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
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionNews($id)
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		$this->view->params['title'] = Yii::t('form', 'Новость');
		
        return $this->render('news', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('messages', 'Эта страница не существует!'));
        }
    }
}
