<?php
use yii\helpers\Html;

if(!empty($paymentsTypes) && isset($paymentsTypes[$type][2]))
{
	echo $this->render('partial/'.$paymentsTypes[$type][2], [
		'id' => $id, 
		'content' => $content, 
		'activation_amount' => $activation_amount, 
		'login' => $model->login.', structure:'.$structure_number.', matrix:'.$matrix_number.', places:'.$places,
	]);
}
?>
