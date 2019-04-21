<?php
namespace frontend\controllers;

use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\components\perfectmoney\actions\ResultAction;
use common\components\perfectmoney\Api;
use common\components\perfectmoney\events\GatewayEvent;
use common\modules\backoffice\models\Partners;
use common\modules\backoffice\models\Payments;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\Matrix1;
use common\components\perfectmoney\models\PerfectMoney;
use common\models\Service;

class PerfectMoneyController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
        /** @var Api $pm */
        $pm = \Yii::$app->get('pm');
        $pm->on(GatewayEvent::EVENT_PAYMENT_REQUEST, [$this, 'handlePaymentRequest']);
        $pm->on(GatewayEvent::EVENT_PAYMENT_SUCCESS, [$this, 'handlePaymentSuccess']);
    }

    public function actions()
    {
        return [
            'result' => [
                'class' => ResultAction::className(),
                'componentName' => 'pm',
                'redirectUrl' => ['/site/index'],
            ],
        ];
    }

    /**
     * @param GatewayEvent $event
     * @return bool
     */
    public function handlePaymentRequest($event)
    {
        /*$invoice = PerfectMoney::findOne(ArrayHelper::getValue($event->gatewayData, 'PAYMENT_ID'));

        if (!$invoice instanceof PerfectMoney ||
            ArrayHelper::getValue($event->gatewayData, 'PAYMENT_AMOUNT') != $invoice->amount ||
            ArrayHelper::getValue($event->gatewayData, 'PAYEE_ACCOUNT') != \Yii::$app->get('pm')->walletNumber
        )
            return;

        $invoice->debugData = VarDumper::dumpAsString($event->gatewayData);
        $event->invoice = $invoice;*/
        
        $event->handled = true;
    }

    /**
     * @param GatewayEvent $event
     * @return bool
     */
    public function handlePaymentSuccess($event)
    {
        $invoice = $event->invoice;

        // TODO: invoice processing goes here 
    }
    
    public function actionPaymentSuccess()
    {
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		$id = \Yii::$app->session->get('activation.partner_id');
		$credit = \Yii::$app->session->get('activation.credit');
		$matrix = \Yii::$app->session->get('activation.matrix');
		$places = \Yii::$app->session->get('activation.places');
		
		if($id > 0 && $matrix > 0 && $places > 0)
		{	
			$model = new Matrix();
			
			if($model->activateByPaymentSystem($id, $credit, $matrix, $places))
			{	
				$class = 'success';
				$msg = Yii::t('messages', 'Вы активированы!');
			}
		}
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
			
		return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/activation');
	}
	
	public function actionPaymentFailure()
    {
		$class = 'error';
		$msg = Yii::t('messages', 'Ошибка!');
		
		\Yii::$app->getSession()->setFlash($class, Yii::t('messages', $msg));
			
		return $this->redirect(\Yii::$app->request->BaseUrl.'/partners/activation');
	}
}
