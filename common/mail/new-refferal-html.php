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
			<?= $first_name ?>&nbsp;<?= $last_name ?>, we congratulate you!<br /><br /> 

			<p>The New Member has registered by your refferels's link!<br /> 

			His login is : <?= $refferal_name ?><br /><br />

			 - perfect Job! Carry on!<br /> 
			Keep going!<br /><br /> 

			Log in your account<br /> 
			see your activities in menu "Your income":<br /><br /> 
			
			<?= $site ?>/login<br />
			</p>
		</td>	
	</tr>
	
</table>
</body>
</html>
