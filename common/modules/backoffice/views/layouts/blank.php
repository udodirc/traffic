<?php
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="<?= Yii::$app->charset ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" rel="stylesheet" href="#" id="skin_change"/>
		<title><?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?></title>
	</head>
	<body>
	<?php $this->beginBody() ?>
	<div class="wrap">
		<?= $content ?>
	</div>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
