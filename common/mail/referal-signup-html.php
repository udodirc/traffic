<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>New member</title>
	<style type="text/css">
        * {font-family:tahoma;} 
    </style>
</head>
<body>
<table style="width:500px; height:600px; background:#eee; border:0;" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:20px;"></td>
		<td style="vertical-align:top;">
			<p>Congratulations!, <span style="color:#002857;"><?= $login ?>!</span></p>
			<p>New memeber is registered by your refferal link,</p> 
			<p>his username is :<b><?= $referal_login ?></b></p>
			<p>Log in you profile in "Stats" menu&nbsp;<a href="<?= $site ?>/login"><?= $site ?>/login</a></p>
			<p>Perfect job! Keep this up!</p>
			<p>We believe in you!</p>
			<br/>
			<p>With king regard, support team. <br /><a  style="color:#002857;" href="<?= $site ?>"><?= $site ?></a></p>
		</td>	
	</tr>
</table>
</body>
</html>
