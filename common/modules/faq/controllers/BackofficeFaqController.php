<?php
namespace common\modules\faq\controllers;

use Yii;
use common\modules\faq\models\Faq;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackofficeFaqControllerController implements the CRUD actions.
 */
class BackofficeFaqController extends Controller
{
	public $layout = 'backoffice';
	public $theme = '';
	protected $user_id;
	protected $identity_id;
	
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
    
    public function beforeAction($event)
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
     * Renders the index view for the module
     * @return string
    */
    public function actionIndex()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
        $faqList = Faq::find()->where(['type'=>2])->all();
		
        return $this->render('index'.$this->theme, [
            'faqList'=>$faqList,
            'theme' => $this->theme
        ]);
    }
}

