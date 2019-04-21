<?php
use frontend\assets\BackOfficeAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;

/* @var $this \yii\web\View */
/* @var $content string */

BackOfficeAppAsset::register($this);
$bundle = BackOfficeAppAsset::register($this);

$frontendMenu = new FrontendMenu();
$status = (isset($this->params['status'])) ? $this->params['status'] : 0;
$demoTotalAmount = (isset($this->params['demo_total_amount'])) ? $this->params['demo_total_amount'] : 0;
$totalAmount = (isset($this->params['total_amount'])) ? $this->params['total_amount'] : 0;
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
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
    <?php include_once("analyticstracking.php") ?>
    <div class="preloader" style=" position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		z-index: 100000;
		backface-visibility: hidden;
		background: #ffffff;">
		<div class="preloader_img" style="width: 200px;
			height: 200px;
			position: absolute;
			left: 48%;
			top: 48%;
			background-position: center;
			z-index: 999999">
			<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'loader.gif'), ['alt'=>'loading...', 'class'=>'width: 40px;']) ?>
		</div>
	</div>
	<div class="bg-dark" id="wrap">
		<div id="top">
			<!-- .navbar -->
			<nav class="navbar navbar-static-top">
				<div class="container-fluid">
					<a class="navbar-brand text-xs-center" href="index.html">
						<h3><?= Yii::$app->name; ?></h3>
					</a>
					<div class="menu">
						<span class="toggle-left" id="menu-toggle">
							<i class="fa fa-bars"></i>
						</span>
					</div>
					<div class="topnav dropdown-menu-right float-xs-right">
						<div class="btn-group hidden-md-up small_device_search" data-toggle="modal"
							 data-target="#search_modal">
							<i class="fa fa-search text-primary"></i>
						</div>
						<div class="btn-group">
							<div class="user-settings no-bg">
								<button type="button" class="btn btn-default no-bg micheal_btn" data-toggle="dropdown">
									<strong>
									<?php if(Yii::$app->user->identity !== null): ?>	
										<?= (Yii::$app->user->identity->first_name != '') ? Yii::$app->user->identity->first_name : ''?>
									<?php endif; ?>
									</strong>
									<span class="fa fa-sort-down white_bg"></span>
								</button>
								<div class="dropdown-menu admire_admin">
									<?php if(Yii::$app->user->identity !== null): ?>
									<a class="dropdown-item title" href="<?=\Yii::$app->request->BaseUrl; ?>/partners/profile/<?= \Yii::$app->user->identity->id ?>">
										<?= Yii::t('menu', 'Профиль'); ?>
									</a>
									<a class="dropdown-item" href="<?=\Yii::$app->request->BaseUrl; ?>/logout">
										<i class="fa fa-sign-out"></i>
										<?= Yii::t('menu', 'Выход'); ?>
									</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.container-fluid -->
			</nav>
			<!-- /.navbar -->
			<!-- /.head -->
		</div>
		<!-- /#top -->
		<div class="wrapper">
			<div id="left">
				<div class="menu_scroll">
					<div class="left_media">
						<div class="media user-media bg-dark dker">
							<div class="user-media-toggleHover">
								<span class="fa fa-user"></span>
							</div>
							<div class="user-wrapper bg-dark">
								<a class="user-link" href="#">
									<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'admin.jpg'), ['alt'=>'User Picture', 'class'=>'media-object img-thumbnail user-img rounded-circle admin_img3']) ?>
									<p class="user-info menu_hide">
									<?php if(Yii::$app->user->identity !== null): ?>	
										<?= Yii::t('menu', 'Здравствуйте'); ?>&nbsp;<?= (Yii::$app->user->identity->first_name != '') ? Yii::$app->user->identity->first_name : ''?>
									<?php endif; ?>
									</p>
								</a>
							</div>
						</div>
						<hr/>
					</div>
					<?= MenuWidget::widget([
						'items' => FrontendMenu::getMenuList(1, true),
						'options' => [
							'id'=>"menu"
						],
						'submenuTemplate' => "\n<ul>\n{items}\n</ul>\n",
					]);
					?>
					<!-- /#menu -->
				</div>
			</div>
			<!-- /#left -->
			<div id="content" class="bg-container">
				<header class="head">
					<div class="main-bar row">
						<div class="col-lg-6 col-sm-4">
							<h4 class="nav_top_align">
								<?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?>
							</h4>
						</div>
					</div>
				</header>
				<div class="outer">
					<?= $content ?>
				</div>
				<!-- /.outer -->
			</div>
		</div>
	</div>
<!-- /#wrap -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
