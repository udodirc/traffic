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
		<title><?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?></title>
		<?php $this->head() ?>
	</head>
	<body class="nav-md">
    <?php $this->beginBody() ?>
    <?php include_once("analyticstracking.php") ?>
    <div class="container body">
		<div class="main_container">
			<div class="col-md-3 left_col">
				<div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">
						<a href="<?=\Yii::$app->request->BaseUrl; ?>" class="site_title">
							<i class="fa fa-paw"></i> 
							<span><?= Yii::t('menu', 'Личный кабинет'); ?></span>
						</a>
					</div>
					<div class="clearfix"></div>
					<!-- menu profile quick info -->
					<div class="profile">
						<div class="profile_pic">
							<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'bg_avatar.png'), ['alt'=>'', 'class'=>'img-circle profile_img']) ?>
						</div>
						<?php if(Yii::$app->user->identity !== null): ?>
						<div class="profile_info">
							<span><?= Yii::t('menu', 'Здравствуйте'); ?>,</span>
							<h2><?= (Yii::$app->user->identity->first_name != '') ? Yii::$app->user->identity->first_name : ''?></h2>
						</div>
						<?php endif; ?>
					</div>
					<!-- /menu profile quick info -->
					<br />
					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<h3><?= Yii::t('menu', 'Меню'); ?></h3>
							<?= MenuWidget::widget([
								'items' => FrontendMenu::getMenuList(1, true),
								'options' => [
									'class'=>"nav side-menu"
								],
								'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
							]);
							?>
						</div>
					</div>
					<!-- /sidebar menu -->
				</div>
			</div>
			<!-- top navigation -->
			<div class="top_nav">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i></a>
						</div>
						<div class="top_tiles">
							<div class="col-md-2 tile">
								<?= Html::a('<span>'.Yii::t('menu', 'Всего заработано').'</span>
								<h2>
									'.$totalAmount.' - <span class="btc">btc</span>
								</h2>', \Yii::$app->request->BaseUrl.'/partners/matrices-structure/0', []); 
								?>
							</div>
							<div class="col-md-2 tile">
								<?= Html::a('<span>'.Yii::t('menu', 'Ожидаемый заработок').'</span>
								<h2>
									'.$demoTotalAmount.' - <span class="btc">btc</span>
								</h2>', \Yii::$app->request->BaseUrl.'/partners/matrices-structure/1', []); 
								?>
							</div>
							<div class="col-md-2 tile">
								<?= Html::a('<span>'.Yii::t('menu', 'Бонус').'</span>
								<h2>
									'.((isset($this->params['register_bonus'])) ? $this->params['register_bonus'] : 0).'$
								</h2>', \Yii::$app->request->BaseUrl.'/backoffice-content/bonus', []); 
								?>
							</div>
							<div class="col-md-2 tile">
								<?= Html::a('<span>'.Yii::t('menu', 'Токены').'</span>
								<h2>
									'.((isset($this->params['register_tokens'])) ? $this->params['register_tokens'] : 0).'
								</h2>', \Yii::$app->request->BaseUrl.'/backoffice-content/tokens', []); 
								?>
							</div>
							<div class="col-md-2 tile">
								<?= Html::a('<span>'.Yii::t('menu', 'Регистраций сегодня').'</span>
								<h2>
									'.((isset($this->params['curr_day_register']) && !empty($this->params['curr_day_register'])) ? $this->params['curr_day_register'] : 0).'
								</h2>', \Yii::$app->request->BaseUrl.'/backoffice-content/curr-day-register', []); 
								?>
							</div>
							<div class="col-md-2 tile">
								<?= Html::a('<span>'.Yii::t('menu', 'Всего партнеров').'</span>
								<h2>
									'.((isset($this->params['total_register'])) ? $this->params['total_register'] : 0).'
								</h2>', \Yii::$app->request->BaseUrl.'/backoffice-content/total-register', []); 
								?>
							</div>
						</div>
						<ul class="nav navbar-nav navbar-right">
							<li class="">
								<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'bg_avatar.png'), ['alt'=>'']) ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<?php if(Yii::$app->user->identity !== null): ?>
								<ul class="dropdown-menu dropdown-usermenu pull-right">						
									<li><a href="<?=\Yii::$app->request->BaseUrl; ?>/partners/profile/<?= \Yii::$app->user->identity->id ?>"><?= Yii::t('menu', 'Профиль'); ?></a></li>
									<li><a href="<?=\Yii::$app->request->BaseUrl; ?>/logout"><i class="fa fa-sign-out pull-right"></i><?= Yii::t('menu', 'Выход'); ?></a></li>	
								</ul>
								<?php endif; ?>
							</li>
						</ul>
					</nav>
				</div>
			</div>
			<!-- /top navigation -->
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="row"><?= $content ?></div>
			</div>
			<!-- footer content -->
			<footer>
				<div class="counter">
					<!--LiveInternet counter--><script type="text/javascript"><!--
					new Image().src = "//counter.yadro.ru/hit?r"+
					escape(document.referrer)+((typeof(screen)=="undefined")?"":
					";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
					screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
					";"+Math.random();//--></script><!--/LiveInternet-->
				</div>
				<div class="clearfix"></div>
			</footer>
			<!-- /footer content -->
		</div>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
