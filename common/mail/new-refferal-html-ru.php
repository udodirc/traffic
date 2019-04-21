<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Index</title>
	<style type="text/css">
        * {font-family:tahoma;} 
    </style>
</head>
<body>
<table style="width:500px; border:0;" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:20px;"></td>
		<td style="vertical-align:top;">
			----------------------------<br />
			<?= $first_name ?>&nbsp;<?= $last_name ?>, Поздравляем Вас!<br /><br /> 

			<p>По вашей ссылке зарегистрировался Новый Участник!<br /> 

			Его логин: <?= $refferal_name ?><br /><br />

			 - отличная Работа! так держать!<br /> 
			Продолжайте в том же духе!<br /><br /> 

			зайдите в Аккаунт и посмотрите продвижение<br /> 
			в Площадках в разделе "Ваш Заработок":<br /><br /> 
			
			<?= $site ?>/login<br />
			</p>
		</td>	
	</tr>
	
</table>
</body>
</html>
