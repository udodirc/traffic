<?php
use yii\helpers\Html;

if($activation_amount > 0)
{
	echo \yarcode\advcash\RedirectForm::widget([
		'api' => Yii::$app->get('advCash'),
		'invoiceId' => $id,
		'amount' => $activation_amount,
		'description' => 'login - '.$login,
	]);
}
else
{
	echo ($content !== null) ? $content->content : '';
}




