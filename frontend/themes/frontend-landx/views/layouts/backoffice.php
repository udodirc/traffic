<?php
use frontend\assets\BackOfficeHiliteAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use common\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;
use common\modules\tickets\widgets\TicketsListWidget;
use common\modules\seo\widgets\CounterWidget;

/* @var $this \yii\web\View */
/* @var $content string */

BackOfficeHiliteAppAsset::register($this);
$bundle = BackOfficeHiliteAppAsset::register($this);

$frontendMenu = new FrontendMenu();
$title = (isset($this->params['title'])) ? $this->params['title'] : '';
$ticketsList = (isset($this->params['tickets_list'])) ? $this->params['tickets_list'] : '';
$ticketsCount = (isset($this->params['tickets_count'])) ? $this->params['tickets_count'] : 0;
$ticketsMesagesCount = (isset($this->params['tickets_mesages_count'])) ? $this->params['tickets_mesages_count'] : 0;
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
	<body class="page-body" data-url="http://bitcoooin.com">
    <?php $this->beginBody() ?>
    <?php include_once("analyticstracking.php") ?>
    <div class="container-scroller">
		<!-- partial:../../partials/_navbar.html -->
		<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
			<div class="text-left navbar-brand-wrapper d-flex align-items-center justify-content-between">
				<?= Html::a(Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'logo.svg'), ['alt'=>'logo']), '/', ['class' => 'navbar-brand brand-logo']); ?>
				<?= Html::a(Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'logo-mini.svg'), ['alt'=>'logo']), '/', ['class' => 'navbar-brand brand-logo-mini']); ?>
				<button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
					<span class="mdi mdi-menu"></span>
				</button>
			</div>
			<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
				<ul class="navbar-nav navbar-nav-right">
					<li class="nav-item dropdown">
						<a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
							<i class="mdi mdi-bell-outline mx-0"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
							<p class="mb-0 font-weight-normal float-left dropdown-header"><?= Yii::t('menu', 'Запросы'); ?></p>
							<?= TicketsListWidget::widget([
								'item_list' => $ticketsList,
								'templateName' => 'hi-lite',
							]);
							?>
						</div>
					</li>
				</ul>
				<?php if(\Yii::$app->user->identity !== null): ?>
				<ul class="navbar-nav">
					<li class="nav-item  dropdown d-none align-items-center d-lg-flex d-none">
						<a class="dropdown-toggle btn btn-outline-secondary btn-fw"  href="#" data-toggle="dropdown" id="pagesDropdown">
							<span class="nav-profile-name"><?= Yii::t('menu', 'Профиль'); ?></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="pagesDropdown">
							<?= Html::a ('<i class="mdi mdi-settings text-primary"></i>'.Yii::t('menu', 'Профиль'), \Yii::$app->request->BaseUrl.'/partners/profile/'.\Yii::$app->user->identity->id, ['class' => 'dropdown-item']); ?>
							<?= Html::a ('<i class="mdi mdi-logout text-primary"></i>'.Yii::t('menu', 'Выход'), \Yii::$app->request->BaseUrl.'/logout', ['class' => 'dropdown-item']); ?>
						</div>
					</li>
				</ul>
				<?php endif: ?>
			</div>
		</nav>
		<!-- partial -->
		<div class="container-fluid page-body-wrapper">
			<!-- partial:../../partials/_sidebar.html -->
			<nav class="sidebar sidebar-offcanvas" id="sidebar">
			<?= MenuWidget::widget([
				'items' => FrontendMenu::getMenuList(1, true),
				'options' => [
					'class'=>'nav'
				],
				'itemOptions'=>['class'=>'nav-item'],
				'linkTemplate' => '<a href="{url}" class="nav-link">
					<i class="mdi mdi-view-headline menu-icon"></i>
					<span class="menu-title">{label}</span>
				</a>',
				'parentSubmenuLinkTemplate' => '<a href="{url}" class="nav-link" data-toggle="collapse" href="#{ui}" aria-expanded="false" aria-controls="{ui}">
					<i class="mdi mdi-view-headline menu-icon"></i>
					<span class="menu-title">{label}</span>
					<i class="menu-arrow"></i>
				</a>',
				'submenuLinkTemplate' => '<a href="{url}" class="nav-link">
					{label}
				</a>',
				'submenuTemplate' => '<div class="collapse" id="{ui}"><ul class="nav flex-column sub-menu">{items}</ul></div>',
			]);
			?>
			</nav>
			<!-- partial -->
			<div class="main-panel">
				<div class="content-wrapper">
					<?= $content ?>
				</div>
				<!-- content-wrapper ends -->
				<!-- partial:../../partials/_footer.html -->
				<div class="footer-wrapper">
					<footer class="footer">
						<div class="d-sm-flex justify-content-center justify-content-sm-between">
							<span class="text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; <?= date("Y"); ?> <?= Yii::$app->name; ?>. All rights reserved. </span>
						</div>
					</footer>
				</div>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
	</div>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

