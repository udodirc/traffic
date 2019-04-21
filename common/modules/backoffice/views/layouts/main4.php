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
$brandSlogan = (isset($this->params['brand_slogan'])) ? $this->params['brand_slogan'] : '';
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
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		<title><?= $this->title; ?></title>
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
		<?php include_once("analyticstracking.php") ?>
		<div class="page">
			<!--========================================================
                            HEADER
			=========================================================-->
			<header>
				<section class="clearfix">
					<div class="container">
						<div class="block_login">
							<a href="<?=\Yii::$app->request->BaseUrl; ?>/signup" class="btn btn-default">
								<span class="fa fa-user"></span><?= Html::encode(Yii::t('menu', 'Регистрация')) ?><span class="fa fa-sort-desc"></span>
							</a>
							<a href="<?=\Yii::$app->request->BaseUrl; ?>/login" class="btn btn-primary"><span class="fa fa-lock"></span><?= Html::encode(Yii::t('menu', 'Вход')) ?></a>
						</div>
						<div class="navbar-header">
							<h1 class="navbar-brand">
								<a data-type='rd-navbar-brand' href="./"><?= Yii::$app->name; ?></a>
								<span class="brand_slogan">
									<?= $brandSlogan; ?>
								</span>
							</h1>
						</div>
					</div>
					<div class="container">
						<form class="search-form" action="search.php" method="GET" accept-charset="utf-8">
							<label class="search-form_label">
								<input class="search-form_input" type="text" name="s" autocomplete="off" placeholder=""/>
								<span class="search-form_liveout"></span>
							</label>
							<button class="search-form_submit fa-search" type="submit"></button>
						</form>
					</div>
				</section>
				<div id="stuck_container" class="stuck_container">
					<nav class="navbar navbar-default navbar-static-top ">
						<div class="container">
							<!-- #menu -->
							<?= MenuWidget::widget([
								'items' => FrontendMenu::getMenuList(2),
								'options' => [
									'class'=>"navbar-nav sf-menu",
									'data-type'=>"navbar",
								],
								'submenuTemplate' => "\n<ul>\n{items}\n</ul>\n",
							]);
							?>
							<!-- /#menu -->
						</div>
					</nav>
				</div>
			</header>
			<!--========================================================
                            CONTENT
			=========================================================-->
			<main>
				<?= $content ?>
			</main>
			<!--========================================================
                              FOOTER
			=========================================================-->
			<footer>
				<div class="container">
					<p class="navbar-brand brand__footer">
						<a href="./"><?= Yii::$app->name; ?></a>
						<span class="brand_slogan"><?= $brandSlogan; ?></span>
					</p>
					<p class="copyrights">
						Наши Контакты: psychotransform.ru@gmail.com,    +996550533955  - whatsapp
					</p>
					<div class="counter">
						<!--LiveInternet counter-->
						<script type="text/javascript">
						document.write("<a href='//www.liveinternet.ru/click' "+
						"target=_blank><img src='//counter.yadro.ru/hit?t45.2;r"+
						escape(document.referrer)+((typeof(screen)=="undefined")?"":
						";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
						screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
						";"+Math.random()+
						"' alt='' title='LiveInternet' "+
						"border='0' width='31' height='31'><\/a>")
						</script>
						<!--/LiveInternet-->
					</div>
				</div>
			</footer>
		</div>
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
