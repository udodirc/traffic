<html>
<head>
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?= Yii::t('form', 'perfectmoneyprofit.com'); ?></title>
</head>
<body>
<table style="width:500px; margin-bottom:20px; background:#eee; border:0;" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<?= Yii::t('form', '<p align="center"><font color="#FFFFFF">&nbsp; <b>
			<font face="Tahoma" size="4">&quot; Perfect Money Profit &quot;</font></b></font>'); 
			?>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top;">
			<p><font face="Tahoma" size="5">Dear, <span style="color:#002857;"><?= $username; ?>!</font></p>
			<p><font face="Tahoma" size="3">You are subscribed on mailing</font></p>						
			<p>
				<font face="Tahoma" size="3">in site <span lang="en-us"><a href="<?= $site; ?>"><?= $site; ?></a></span>
			</p>
				<p></p>	
				&nbsp;
			</p>
			<p>&nbsp;</p>
			<font face="Tahoma" size="5">Message:</font></p>
			<p face="Tahoma" size="5"><?= $message; ?></p>	
			&nbsp;</p>
			<p>
				&nbsp;
			</p>
			<p>
				<font face="Tahoma">You can write us here:</font>
			</p>
			<p>
			<font face="Tahoma">Our contacts: </font> </p>
			<p>
			<font face="Tahoma"><?= $admin_email; ?></font></p>
			<p>&nbsp;</p>
			<p>---------------------<span lang="en-us">------------</span></p>
			<p>Best regards, support team. <br/><a  style="color:#002857;" href="<?= $site; ?>"><?= $site; ?></a></p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</td>	
	</tr>
</table>
</body>
</html>
