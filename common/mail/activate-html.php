<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Activation</title>
	<style type="text/css">
        * {font-family:tahoma;} 
    </style>
</head>
<body>
<table style="width:500px; margin-bottom:20px; background:#eee; border:0;" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:20px;"></td>
		<td style="vertical-align:top;">
			<p><span style="color:red;">Congratulations&nbsp;-&nbsp;<?= $login ?>!</span></p>
			<br/>
			<p>Your first platform is activated!</p>
			<p>Please check your platform in site:</p>
			<p><a href="<?= $site ?>/login"><?= $site ?>/login</a></p>
			<br/>
			<br/>
			<p>Best regards, support team. <br /><a  style="color:#002857;" href="<?= $site ?>"><?= $site ?></a></p>
			<br/>
			<br/>
		</td>	
	</tr>
</table>
</body>
</html>
