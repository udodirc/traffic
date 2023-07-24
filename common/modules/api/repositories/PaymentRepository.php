<?php

namespace common\modules\api\repositories;

use common\modules\structure\models\InvitePayOff;
use common\modules\structure\models\MatrixPayments;

class PaymentRepository
{
	private MatrixPayments $matrixPaymentsModel;

	public function __construct
	(
		MatrixPayments $matrixPaymentsModel
	)
	{
		$this->matrixPaymentsModel = $matrixPaymentsModel;
	}

	public function getMatrixPaymentData(): array
	{
		return MatrixPayments::find()
			->joinWith(['partner'])
			->where(['matrix_payments.type'=>2, 'matrix_payments.paid_off'=>0])
			->asArray()
			->all();
	}

	public function getInviteData(): array
	{
		return InvitePayOff::find()
		->joinWith(['partner'])
		->where(['invite_pay_off.paid_off'=>0])
		->asArray()
		->all();
	}
}