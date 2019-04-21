<?php

namespace common\modules\structure\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\modules\tickets\models\Tickets;
use common\modules\tickets\models\TicketsMessages;
use common\modules\structure\models\InvitePayOff;
use common\modules\structure\models\MatrixPayments;
use common\modules\structure\models\RegisterStats;
use common\modules\structure\models\Withdrawal;
use common\modules\backoffice\models\Partners;
use common\modules\backoffice\models\forms\LoginForm;
use frontend\models\FeedbackForm;
use common\models\StaticContent;
use common\models\Service;

/**
 * FrontendWithdrawalController implements the CRUD actions for Withdrawal model.
 */
class FrontendWithdrawalController extends Controller
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
		if(!Service::isActionAllowed('is_withdrawal_allowed'))
		{	
			throw new NotFoundHttpException(Yii::t('messages', 'У вас нет прав!'));
		}
		
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
     * Displays a withrawal data.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $id;
		$this->identity_id = $id;
		
		/*$query = InvitePayOff::find()
		->select(['`withdrawal`.`id`', '`partners`.`login`', '`benefit_partner`.`login`', '`invite_pay_off`.`structure_number`', '`invite_pay_off`.`matrix_number`', '`invite_pay_off`.`matrix_id`', '`invite_pay_off`.`amount`', '`withdrawal`.`created_at`'])
		->leftJoin('`withdrawal`', '`withdrawal`.`item_id` = `invite_pay_off`.`id` AND `withdrawal`.`type` = 2')
		->leftJoin('`partners`', '`partners`.`id` = `invite_pay_off`.`partner_id`')
		->leftJoin('`partners` `benefit_partner`', '`benefit_partner`.`id` = `invite_pay_off`.`benefit_partner_id`')
		->orderBy('`invite_pay_off`.`created_at` DESC');*/
		
		$invitePayOffList = new ActiveDataProvider([
			'query' => InvitePayOff::find()->joinWith(['partner', 'benefitPartner'])->where(['`invite_pay_off`.`benefit_partner_id`'=>$id])->orderBy('`invite_pay_off`.`created_at` DESC'),
			//'query' => $query,
			'pagination' => [
				'pageSize' => 2,
			],
		]);
		
		$matrixPaymentsList = new ActiveDataProvider([
			'query' => MatrixPayments::find()->joinWith(['partner', 'payerPartner'])->where(['matrix_payments.partner_id'=>$id, 'matrix_payments.type'=>2])->orderBy('`matrix_payments`.`created_at` DESC'),
			'pagination' => [
				'pageSize' => 2,
			],
		]);
		
		return $this->render('index', [
			'invitePayOffList' => $invitePayOffList,
			'matrixPaymentsList' => $matrixPaymentsList
		]);
    }
    
    /**
     * Displays a request data.
     * @param integer $id
     * @return mixed
     */
    public function actionRequest($id, $type)
    {
		$partnerID = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$this->user_id = $partnerID;
		$this->identity_id = $partnerID;
		
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
		if($id > 0 && $type > 0)
		{
			if(Withdrawal::makeWithdrawalRequest($id, $type))
			{
				$class = 'success';
				$msg = Yii::t('messages', 'Запрос создан!');
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
			
		return $this->redirect(['index']);
    }
}
