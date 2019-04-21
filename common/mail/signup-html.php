<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Registration</title>
	<style type="text/css">
        * {font-family:tahoma;} 
    </style>
</head>
<body>
<table style="width:500px; height:600px; background:#eee; border:0;" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:20px;"></td>
		<td style="vertical-align:top;">
			<p>Good day, <span style="color:#002857;"><?= $first_name ?>&nbsp;<?= $last_name ?>!</span></p>
			<p>Remember your registration`s requisits:</p>
			<p><b>Login:</b> <span style="color:#002857;"><?= $login ?></span></p>
			<p><b>E-mail:</b> <span style="color:#002857;"><?= $email ?></span></p>
			<p>To confirm your registration follow this link&nbsp;<a href="<?= $site ?>/confirm/<?= $partner_id ?>/<?= $auth_key ?>"><?= $site ?>/confirm/<?= $partner_id ?>/<?= $auth_key ?></a></p>
			<br />
			<p>With king regard, support team. <br /><a  style="color:#002857;" href="<?= $site ?>"><?= $site ?></a></p>
		</td>	
	</tr>
	
</table>
</body>
</html>
