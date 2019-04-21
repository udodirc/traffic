<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Новый реферал</title>
	<style type="text/css">
        * {font-family:tahoma;} 
    </style>
</head>
<body>
<table style="width:500px; height:600px; background:#eee; border:0;" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:20px;"></td>
		<td style="vertical-align:top;">
			<p>Поздравляем Вас!, <span style="color:#002857;"><?= $login ?>!</span></p>
			<p>По вашей ссылке зарегистрировался новый реферал его логин :<span style="color:#002857;"><?= $referal_login ?></span></p>
			<p>Зайдите в свой аккаунт и посмотрите в разделе "Статистика"&nbsp;<a href="<?= $site ?>/login"><?= $site ?>/login</a></p>
			<p>Отличная Работа! Продолжайте в том же духе! Мы верим в ваш успех!</p>
			<br/>
			<p>With king regard, support team. <br /><a  style="color:#002857;" href="<?= $site ?>"><?= $site ?></a></p>
		</td>	
	</tr>
</table>
</body>
</html>
