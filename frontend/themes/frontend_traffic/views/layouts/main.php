<?php
use frontend\assets\FrontendAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;
use common\modules\seo\widgets\CounterWidget;
use common\widgets\BottomMenuWidget;

/* @var $this \yii\web\View */
/* @var $content string */

FrontendAppAsset::register($this);
$bundle = FrontendAppAsset::register($this);
$brandSlogan = (isset($this->params['brand_slogan'])) ? $this->params['brand_slogan'] : '';
$phone = (isset($this->params['phone'])) ? $this->params['phone'] : '';
$location = (isset($this->params['location'])) ? $this->params['location'] : '';
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
		 <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="format-detection" content="telephone=no"/>
		<link rel="icon" href="<?= \Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'favicon.ico'); ?>" type="image/x-icon">
		<title><?= $this->title; ?></title>
		<?php $this->head() ?>
	</head>
	<body id="page-top" class="landing-page no-skin-config">
    <?php $this->beginBody() ?>
    <?php include_once("analyticstracking.php"); ?>
		<div class="main-wrapper">
			<div class="navbar-wrapper">
				<nav class="navbar navbar-default navbar-fixed-top navbar-scroll" role="navigation">
					<div class="container">
						<div class="navbar-header page-scroll">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="/"><?= $brandSlogan; ?></a>
						</div>
						<div id="navbar" class="navbar-collapse collapse">
							<?= MenuWidget::widget([
								'items' => FrontendMenu::getMenuList(2),
								'options' => [
									'class'=>'nav navbar-nav navbar-right',
								],
								'submenuTemplate' => '<ul>{items}</ul>',
							]);
							?>
						</div>
					</div>
				</nav>
			</div>
			<?= $content ?>
		</div>
		<footer>
			<section id="contact" class="gray-section">
				<div class="container">
					<div class="row">
						<div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
							<p><strong>&copy; 2017 <?= Yii::$app->name; ?></strong>
							<div class="counter">
								<?= CounterWidget::widget([
									'counter_name' => 'liveinternet',
								]); ?>
							</div>
						</div>
					</div>
				</div>
			</section>
		</footer>
    <?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
