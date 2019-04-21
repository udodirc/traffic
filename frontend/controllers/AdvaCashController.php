<?php
namespace frontend\controllers;

use Yii;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\Payment;
use common\modules\structure\models\PaymentLogs;
use common\modules\structure\models\MatricesSettings;
use yarcode\advcash\actions\ResultAction;
use yarcode\advcash\events\GatewayEvent;
use common\components\advacash\Api;
use common\components\advacash\Merchant;
use common\components\advacash\models\Advacash;
use common\modules\structure\models\PaymentsInvoices;
use common\modules\structure\models\PaymentsFaul;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

class AdvaCashController extends Controller
{
    /** @inheritdoc */
    public $enableCsrfValidation = false;

    /** @var string Your component configuration name */
    public $componentName = 'advCash';

    /** @var Merchant */
    protected $component;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->component = \Yii::$app->get($this->componentName);

        $this->component->on(GatewayEvent::EVENT_PAYMENT_REQUEST, [$this, 'handlePaymentRequest']);
        $this->component->on(GatewayEvent::EVENT_PAYMENT_SUCCESS, [$this, 'handlePaymentSuccess']);
    }

    public function actions()
    {
        return [
            'result' => [
                'class' => ResultAction::className(),
                'componentName' => $this->componentName,
                'redirectUrl' => ['/billing'],
            ],
            'success' => [
                'class' => ResultAction::className(),
                'componentName' => $this->componentName,
                'redirectUrl' => ['/billing'],
                'silent' => true,
            ],
            'failure' => [
                'class' => ResultAction::className(),
                'componentName' => $this->componentName,
                'redirectUrl' => ['/billing'],
                'silent' => true,
            ]
        ];
    }

    /**
     * @param GatewayEvent $event
     * @return bool
     */
    public function handlePaymentRequest($event)
    {
        $invoice = Invoice::findOne(ArrayHelper::getValue($event->gatewayData, 'ac_order_id'));

        if (!$invoice instanceof Invoice ||
            $invoice->status != Invoice::STATUS_NEW ||
            ArrayHelper::getValue($event->gatewayData, 'ac_amount') != $invoice->amount ||
            ArrayHelper::getValue($event->gatewayData,
                'ac_transaction_status') != Merchant::TRANSACTION_STATUS_COMPLETED ||
            ArrayHelper::getValue($event->gatewayData, 'ac_sci_name') != $this->component->merchantName
        ) {
            return;
        }

        $invoice->debugData = VarDumper::dumpAsString($event->gatewayData);
        $event->invoice = $invoice;
        $event->handled = true;
    }

    /**
     * @param GatewayEvent $event
     * @return bool
     */
    public function handlePaymentSuccess($event)
    {
        /** @var Invoice $invoice */
        $invoice = $event->invoice;

        // TODO: invoice processing goes here
    }
    
    public function actionPaymentFailure()
    {
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$logID = ($id > 0) ? $id : 1;
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes) && $id > 0)
		{
			$paymentLogsModel = new PaymentLogs();
			
			if(\Yii::$app->session->get('activation.partner_id') || \Yii::$app->session->get('activation.structure') || \Yii::$app->session->get('activation.matrix') || \Yii::$app->session->get('activation.places') || \Yii::$app->session->get('activation.payment_type') || ($id = Yii::$app->session->get('activation.partner_id')) || \Yii::$app->session->get('activation.sci_sign'))
			{
				$partnerID = \Yii::$app->session->get('activation.partner_id');
				$structureNumber = \Yii::$app->session->get('activation.structure');
				$matrixNumber = \Yii::$app->session->get('activation.matrix');
				$places = \Yii::$app->session->get('activation.places');
				$paymentType = \Yii::$app->session->get('activation.payment_type');
				$sciSign = \Yii::$app->session->get('activation.sci_sign');
				
				if($id > 0 && $matrixNumber > 0 && $places > 0 && $paymentType > 0 && $sciSign != '')
				{
						
					if(isset($get['ac_dest_wallet']) && isset($get['ac_amount']) && isset($get['ac_currency']) && isset($get['ac_sci_name']) && isset($get['ac_order_id']))
					{	
						$model = new PaymentsFaul();
						$get = Yii::$app->request->get();
						
						$model->partner_id = $partnerID;
						$model->matrix_number = $matrixNumber;
						$model->matrix_id = 0;
						$model->paid_matrix_partner_id = $partnerID;
						$model->paid_matrix_id = 0;
						$model->payment_type = $paymentType;
						$model->amount = $get['ac_amount'];
						$model->note = Yii::t('messages', 'Ошибка платежа!');
						$model->created_at = time();
						$model->save(false);
						
						$msg = Yii::t('messages', 'Ошибка платежа!');
						
						\Yii::$app->session->remove('activation.partner_id');
						\Yii::$app->session->remove('activation.payment_type');
						\Yii::$app->session->remove('activation.structure');
						\Yii::$app->session->remove('activation.matrix');
						\Yii::$app->session->remove('activation.places');
					}
					else
					{
						$paymentLogsModel->insertLogs($partnerID, $structureNumber, $matrixNumber, 0, $places, $paymentType, $sciSign, 0, 0, 0, 3, 3);
					}
				}
				else
				{
					$paymentLogsModel->insertLogs($logID, 0, 0, 0, 0, 0, '', 0, 0, 0, 3, 2);
				}
			}
			else
			{	
				$paymentLogsModel->insertLogs($logID, 0, 0, 0, 0, 0, '', 0, 0, 0, 3, 1);
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, Html::encode(Yii::t('messages', $msg)));
		
		return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/activation');
	}
	
	public function actionPaymentResult()
    {
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$logID = ($id > 0) ? $id : 1;
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes) && $id > 0)
		{
			$paymentLogsModel = new PaymentLogs();
			
			if(\Yii::$app->session->get('activation.partner_id') || \Yii::$app->session->get('activation.structure') || \Yii::$app->session->get('activation.matrix') || \Yii::$app->session->get('activation.places') || \Yii::$app->session->get('activation.payment_type') || ($id = Yii::$app->session->get('activation.partner_id')) || \Yii::$app->session->get('activation.sci_sign'))
			{
				$partnerID = \Yii::$app->session->get('activation.partner_id');
				$structureNumber = \Yii::$app->session->get('activation.structure');
				$matrixNumber = \Yii::$app->session->get('activation.matrix');
				$places = \Yii::$app->session->get('activation.places');
				$paymentType = \Yii::$app->session->get('activation.payment_type');
				$sciSign = \Yii::$app->session->get('activation.sci_sign');
				
				if($partnerID > 0 && $id > 0 && $structureNumber > 0 && $matrixNumber > 0 && $places > 0 && $paymentType > 0 && $sciSign != '')
				{
					$get = Yii::$app->request->get();
						
					if(isset($get['ac_src_wallet']) && isset($get['ac_dest_wallet']) && isset($get['ac_amount'])
					&& isset($get['ac_merchant_amount']) && isset($get['ac_merchant_currency']) && isset($get['ac_fee']) && isset($get['ac_buyer_amount_without_commission'])
					&& isset($get['ac_buyer_amount_with_commission']) && isset($get['ac_buyer_currency']) && isset($get['ac_transfer']) && isset($get['ac_sci_name'])
					&& isset($get['ac_start_date']) && isset($get['ac_order_id']) && isset($get['ac_ps']) && isset($get['ac_transaction_status']) && isset($get['ac_buyer_email'])
					&& isset($get['ac_buyer_verified'])  && isset($get['ac_comments']))
					{
						$orderID = $get['ac_order_id'];
						$amount = $get['ac_amount'];
						$transactID = $get['ac_transfer'];
						$paymentsInvoiceID = \Yii::$app->session->get('activation.invoice_id');
						
						$matrixData = Matrix::getDataFromMatrixByPartnerID($structureNumber, $matrixNumber, $partnerID);
						$matrixID = ($matrixData != null) ? $matrixData['id'] : 0;
								
						if($matrixID > 0 && $paymentType > 0 && $amount > 0 && $transactID != '')
						{
							$paymentsInvoicesModel = new PaymentsInvoices();
							
							if(!$paymentsInvoicesModel->insertInvoice($matrixID, $structureNumber, $matrixNumber, $partnerID, $partnerID, $matrixID, $paymentType, 1, $amount, $transactID))
							{	
								$insertInvoice = false;
								$model = new PaymentsFaul();
								
								$model->partner_id = $partnerID;
								$model->matrix_number = $matrixNumber;
								$model->matrix_id = 0;
								$model->paid_matrix_partner_id = $partnerID;
								$model->paid_matrix_id = 0;
								$model->payment_type = $paymentType;
								$model->amount = $amount;
								$model->note = Yii::t('messages', 'Ошибка платежа!');
								$model->created_at = time();
								$model->save(false);		
							}
							
							\Yii::$app->session->remove('activation.partner_id');
							\Yii::$app->session->remove('activation.payment_type');
							\Yii::$app->session->remove('activation.structure');
							\Yii::$app->session->remove('activation.matrix');
							\Yii::$app->session->remove('activation.places');
						}
						else
						{
							$paymentLogsModel->insertLogs($partnerID, $structureNumber, $matrixNumber, $matrixID, $places, $paymentType, $sciSign, $orderID, $amount, $transactID, 2, 4);
						}
					}
					else
					{
						$paymentLogsModel->insertLogs($partnerID, $structureNumber, $matrixNumber, 0, $places, $paymentType, $sciSign, 0, 0, 0, 2, 3);
					}
				}
				else
				{
					$paymentLogsModel->insertLogs($logID, 0, 0, 0, 0, 0, '', 0, 0, 0, 2, 2);
				}
			}
			else
			{	
				$paymentLogsModel->insertLogs($logID, 0, 0, 0, 0, 0, '', 0, 0, 0, 2, 1);
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, Html::encode(Yii::t('messages', $msg)));
		
		return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/activation');
	}	
	
	public function actionPaymentSuccess()
    {
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
		$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
		$logID = ($id > 0) ? $id : 1;
		$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
		
		if(!empty($paymentsTypes) && $id > 0)
		{
			$paymentLogsModel = new PaymentLogs();
			
			if(\Yii::$app->session->get('activation.partner_id') || \Yii::$app->session->get('activation.structure') || \Yii::$app->session->get('activation.matrix') || \Yii::$app->session->get('activation.places') || \Yii::$app->session->get('activation.payment_type') || ($id = Yii::$app->session->get('activation.partner_id')) || \Yii::$app->session->get('activation.sci_sign'))
			{
				$partnerID = \Yii::$app->session->get('activation.partner_id');
				$structureNumber = \Yii::$app->session->get('activation.structure');
				$matrixNumber = \Yii::$app->session->get('activation.matrix');
				$places = \Yii::$app->session->get('activation.places');
				$paymentType = \Yii::$app->session->get('activation.payment_type');
				$sciSign = \Yii::$app->session->get('activation.sci_sign');
			
				if($id > 0 && $partnerID > 0 && $structureNumber > 0 && $matrixNumber > 0 && $places > 0 && $paymentType > 0 && $sciSign != '')
				{	
					$matricesSettings = Matrix::getMatricesSettings($structureNumber, $matrixNumber);
					
					if(!empty($matricesSettings))
					{
						$model = new Payment();
						$get = Yii::$app->request->get();
						
						if(isset($get['ac_src_wallet']) && isset($get['ac_dest_wallet']) && isset($get['ac_amount'])
						&& isset($get['ac_merchant_amount']) && isset($get['ac_merchant_currency']) && isset($get['ac_fee']) && isset($get['ac_buyer_amount_without_commission'])
						&& isset($get['ac_buyer_amount_with_commission']) && isset($get['ac_buyer_currency']) && isset($get['ac_transfer']) && isset($get['ac_sci_name'])
						&& isset($get['ac_start_date']) && isset($get['ac_order_id']) && isset($get['ac_ps']) && isset($get['ac_transaction_status']) && isset($get['ac_buyer_email'])
						&& isset($get['ac_buyer_verified'])  && isset($get['ac_comments']))
						{	
							$api = new Merchant();
							$orderID = $get['ac_order_id'];
							$amount = $get['ac_amount'];
							$transactID = $get['ac_transfer'];
							$paymentsInvoiceID = \Yii::$app->session->get('activation.invoice_id');
							$getSciSign = $api->createSciSign($amount, $orderID);
							
							if($model->activateByPaymentSystem($id, $structureNumber, $matrixNumber, $places, false, $paymentType, $amount, $transactID))
							{	
								\Yii::$app->session->remove('activation.partner_id');
								\Yii::$app->session->remove('activation.matrix');
								\Yii::$app->session->remove('activation.places');
								\Yii::$app->session->remove('activation.payment_type');
								\Yii::$app->session->remove('activation.partner_id');
								\Yii::$app->session->remove('activation.sci_sign');
											
								$class = 'success';
								$msg = Yii::t('messages', 'Поздравляем!').' '.Yii::t('messages', 'Вы активированы.').' - '.Yii::t('messages', 'Зайдите в раздел').' '.Yii::t('messages', 'Ваш Реальный Заработок');
							}
						}
						else
						{
							$paymentLogsModel->insertLogs($partnerID, $structureNumber, $matrixNumber, 0, $places, $paymentType, $sciSign, 0, 0, 0, 1, 3);
						}
					}
					else
					{
						$paymentLogsModel->insertLogs($partnerID, $structureNumber, $matrixNumber, 0, $places, $paymentType, $sciSign, 0, 0, 0, 1, 2);
					}
				}
				else
				{
					$paymentLogsModel->insertLogs($logID, 0, 0, 0, 0, 0, '', 0, 0, 0, 1, 2);
				}
			}
			else
			{	
				$paymentLogsModel->insertLogs($logID, 0, 0, 0, 0, 0, '', 0, 0, 0, 1, 1);
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, Html::encode(Yii::t('messages', $msg)));
		
		return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/activation');
	}
}
