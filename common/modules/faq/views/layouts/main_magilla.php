<?php
use frontend\assets\FrontendAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;

/* @var $this \yii\web\View */
/* @var $content string */

FrontendAppAsset::register($this);
$bundle = FrontendAppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
	<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
	<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
	<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width">
		<title><?= $this->title; ?></title>
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
		<?php include_once("analyticstracking.php") ?>
		<header class="span1">
		<div class="inner cf">
			<div class="logo">
				<a href="/">
					<span><?= Yii::$app->name; ?></span>
				</a>
			</div>
			<nav class="top-menu">
				<?= MenuWidget::widget([
					'items' => FrontendMenu::getMenuList(2),
					'options' => [
						'class'=>"cf"
					]
				]);
				?>
			</nav>
			<div class="login right">
				<a class="btn white" href="<?=\Yii::$app->request->BaseUrl; ?>/login">Логин</a>
			</div>
			<div class="register right">
				<a class="btn white" href="<?=\Yii::$app->request->BaseUrl; ?>/signup">Регистрация</a>
			</div>
			<div class="register right">
				<a class="btn white" href="<?=\Yii::$app->request->BaseUrl; ?>/contacts">Обратная связь</a>
			</div>
		</div>
		</header>
		<div id="container" class="cf">
			<div class="content_wrap">
				<?= $content ?>
			</div>
		</div> <!--! end of #container -->
		<footer>
			<div class="counter">
				<!--LiveInternet counter--><script type="text/javascript"><!--
				new Image().src = "//counter.yadro.ru/hit?r"+
				escape(document.referrer)+((typeof(screen)=="undefined")?"":
				";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
				screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
				";"+Math.random();//--></script><!--/LiveInternet-->
			</div>
		</footer>
    <?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
