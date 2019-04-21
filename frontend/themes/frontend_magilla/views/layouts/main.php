<?php
use frontend\assets\FrontendAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;
use common\modules\seo\widgets\CounterWidget;

/* @var $this \yii\web\View */
/* @var $content string */

FrontendAppAsset::register($this);
$bundle = FrontendAppAsset::register($this);
$brandSlogan = (isset($this->params['brand_slogan'])) ? $this->params['brand_slogan'] : '';

$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/gsap/TweenMax.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/bootstrap.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/joinable.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/resizeable.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/neon-slider.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/neon-custom.js',['depends' => [\yii\web\JqueryAsset::className()]]);
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
	<body>
    <?php $this->beginBody() ?>
		<?php include_once("analyticstracking.php") ?>
		<div class="wrap">
			<!-- Logo and Navigation -->
			<div class="site-header-container container">
				<div class="row">
					<div class="col-md-12">
						<header class="site-header">
							<section class="site-logo">
								<a href="/">
									<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'logo2.png'), ['alt'=>'logo', 'width'=>'200px']) ?>
								</a>
							</section>
							<nav class="site-nav">
								<?= MenuWidget::widget([
									'items' => FrontendMenu::getMenuList(2),
									'options' => [
										'id'=>'main-menu',
										'class'=>'main-menu hidden-xs',
									],
									'submenuTemplate' => '<ul>{items}</ul>',
								]);
								?>
								<div class="visible-xs">
									<a href="#" class="menu-trigger">
										<i class="entypo-menu"></i>
									</a>
								</div>
							</nav>
						</header>
					</div>
				</div>
			</div>
			<hr class="head_line"/>
			<!-- #content -->
			<?= $content ?>
			<!-- Site Footer -->
			<footer class="site-footer">
				<div class="container">
					<div class="row">
						<div class="col-sm-6">
							Copyright &copy; <?= Yii::$app->name; ?> - <?= date("Y"); ?> 
						</div>
						<div class="counter">
							<?= CounterWidget::widget([
								'counter_name' => 'liveinternet',
							]); ?>
						</div>
					</div>
				</div>
			</footer>	
		</div>
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
