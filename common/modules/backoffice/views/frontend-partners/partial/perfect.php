<?php
use yii\helpers\Html;

if($activation_amount > 0)
{
	echo \common\components\perfectmoney\RedirectForm::widget([
		'api' => Yii::$app->get('pm'),
		'invoiceId' => $id,
		'amount' => $activation_amount,
		'description' => 'login - '.$login,
	]);
}
else
{
	echo ($content !== null) ? $content->content : '';
}




