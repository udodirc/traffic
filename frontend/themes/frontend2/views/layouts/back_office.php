<?php
use Yii;
use frontend\assets\FrontendAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu;
use common\models\Menu as FrontendMenu;
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $content string */

FrontendAppAsset::register($this);
$bundle = FrontendAppAsset::register($this);

$frontendMenu = new FrontendMenu();
$model = $this->params['model'];
$status = (isset($this->params['status'])) ? $this->params['status'] : 0;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <header>
	<div class="container">
		<div class="header-top">
			<div class="logo">
				<a href="<?= Url::base();?>"></a>
			</div>
			<div class="user-account">
			<?php if(Yii::$app->user->identity !== null): ?>
				<div class="ninecol last"><span><?= Yii::t('messages', 'Добро пожаловать'); ?>, <strong><?= \Yii::$app->user->identity->login ?>!</strong></span><span>&nbsp;|&nbsp;</span><a href="<?=\Yii::$app->request->BaseUrl; ?>/partners/profile/<?= \Yii::$app->user->identity->id ?>"><?= Yii::t('menu', 'Профиль'); ?></a><span>&nbsp;|&nbsp;</span><a href="<?=\Yii::$app->request->BaseUrl; ?>/logout"><?= Yii::t('menu', 'Выход'); ?></a></div>
			<?php endif; ?>
			</div>
        </div>
    </div>
	<div class="header-menu">
        <div class="container">
          <ul>
            <?= Menu::widget([
				'items' => $frontendMenu->getBackOfficeMenuList('front_top'),
				'options' => [
					'class'=>"nav-user-options"
				],
				'activeCssClass'=>false,
				'encodeLabels' => false,
				'linkTemplate'=>'<a href="{url}">{label}</a>',
			]); ?>
          </ul>
        </div>
      </div>
    </header>
    <div class="content-block">
      <div class="container">
        <div class="content-wrap">
          <div class="sidebar-menu">
			<div class="status">
				<?= ($status > 0) ? Yii::t('messages', 'Ваш статус <span class="status_view">активен</span>!') : Yii::t('messages', 'Ваш статус не <span class="status_view">активен</span>!'); ?><br/>
				<?= Html::a(Yii::t('form', 'Подробнее'), Url::base().'/status-page/'.$status, []) ?>
            </div>
            <ul>
              <?= Menu::widget([
				'items' => $frontendMenu->getBackOfficeMenuList((Yii::$app->user->identity === null) ? 'front_left' : 'backoffice_left'),
				'options' => [
					'class'=>"nav-user-options"
				],
				'activeCssClass'=>false,
				'encodeLabels' => false,
				'linkTemplate'=>'<a href="{url}">{label}</a>',
			]); ?>
            </ul>
			</div>
			<div class="main-content">
				<?= $content ?>
			</div>
        </div>
      </div>
    </div>
    <footer>
      <div class="container">
        <div class="counter">      
		<!--LiveInternet counter--><script type="text/javascript"><!--
		document.write("<a href='//www.liveinternet.ru/click' "+
		"target=_blank><img src='//counter.yadro.ru/hit?t18.5;r"+
		escape(document.referrer)+((typeof(screen)=="undefined")?"":
		";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
		screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
		";"+Math.random()+
		"' alt='' title='LiveInternet: ïîêàçàíî ÷èñëî ïðîñìîòðîâ çà 24"+
		" ÷àñà, ïîñåòèòåëåé çà 24 ÷àñà è çà ñåãîäíÿ' "+
		"border='0' width='88' height='31'><\/a>")
		//--></script><!--/LiveInternet-->
        </div>
        <div class="footer-menu">
			<?php if(Yii::$app->user->identity === null): ?>
				<?= Menu::widget([
					'items' => $frontendMenu->getBackOfficeMenuList('front_bottom_1'),
					'options' => [
						'class'=>"nav-user-options"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'linkTemplate'=>'<a href="{url}">{label}</a>',
				]); ?>
			<?php endif; ?>
			<?php if(Yii::$app->user->identity === null): ?>
				<?= Menu::widget([
					'items' => $frontendMenu->getBackOfficeMenuList('front_bottom_2'),
					'options' => [
						'class'=>"nav-user-options"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'linkTemplate'=>'<a href="{url}">{label}</a>',
				]); ?>
			<?php endif; ?>
			<?php if(Yii::$app->user->identity === null): ?>
				<?= Menu::widget([
					'items' => $frontendMenu->getBackOfficeMenuList('front_bottom_3'),
					'options' => [
						'class'=>"nav-user-options"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'linkTemplate'=>'<a href="{url}">{label}</a>',
				]); ?>
			<?php endif; ?>
        </div>
        <div class="copyright">
           “Algoritm site” Copyright © algoritmdeneg.com - все права защищены
        </div>
      </div>  
    </footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
