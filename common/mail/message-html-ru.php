<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?= $subject ?></title>
	<style type="text/css">
        * {font-family:tahoma;} 
    </style>
</head>
<body>
<table style="width:500px; height:600px; background:#eee; border:0;" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:20px;"></td>
		<td style="vertical-align:top;">
			<p><?= $message ?></p>
			<br/>
			<br/>
			<p>С уважением, служба поддержки. <br /><a  style="color:#002857;" href="<?= $site ?>"><?= $site ?></a></p>
		</td>	
	</tr>
</table>
</body>
</html>
