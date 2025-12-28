<?php

namespace common\modules\api\controllers;

use common\modules\api\repositories\PaymentRepository;
use yii\web\Controller;

class PaymentController  extends Controller
{
	public function actionGetPaymentData()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$container = \Yii::$container;

		$data['matrix_payments'] = $container->get(PaymentRepository::class)->getMatrixPaymentData();
		$data['invite'] = $container->get(PaymentRepository::class)->getInviteData();

		return [
			'matrix_payments' => $data['matrix_payments'],
			'invite' => $data['invite'],
		];
	}
}