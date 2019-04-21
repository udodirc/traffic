<?php
use Yii;
use frontend\assets\BackendAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu;
use common\models\Menu as FrontendMenu;

/* @var $this \yii\web\View */
/* @var $content string */

BackendAppAsset::register($this);
$bundle = BackendAppAsset::register($this);

$frontendMenu = new FrontendMenu();
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
    <!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold|PT+Sans+Narrow:regular,bold|Droid+Serif:iamp;v1' rel='stylesheet' type='text/css' />	
</head>
<body>
    <?php $this->beginBody() ?>
    <div id="header-wrapper" class="container">
		<div id="user-account" class="row" >
			<div class="threecol"></div>
			<?php if(Yii::$app->user->identity !== null): ?>
				<div class="ninecol last"><a href="<?=\Yii::$app->request->BaseUrl; ?>/site/logout"><?= Yii::t('menu', 'Выход'); ?></a> <span>|</span> <a href="<?=\Yii::$app->request->BaseUrl; ?>/partners/profile/<?= \Yii::$app->user->identity->id ?>"><?= Yii::t('menu', 'Профиль'); ?></a> <span>|</span> <span><?= Yii::t('messages', 'Добро пожаловать'); ?>, <strong><?= \Yii::$app->user->identity->login ?>!</strong></span> </div>
			<?php endif; ?>
		</div>
		<div id="user-options" class="row">
			<div class="threecol"></div>
			<div class="ninecol last fixed">
				<?php if(Yii::$app->user->identity === null): ?>
				<?= Menu::widget([
					'items' => $frontendMenu->getBackOfficeMenuList(1, true),
					'options' => [
						'class'=>"nav-user-options"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'linkTemplate'=>'<a href="{url}">{label}</a>',
				]); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<?php if(Yii::$app->user->identity !== null): ?>
			<div id="sidebar" class="threecol">
				<?= Menu::widget([
					'items' => $frontendMenu->getBackOfficeMenuList(2),
					'options' => [
						'id'=>"navigation"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'firstItemCssClass'=>'first active',
				]); ?>
			</div>
			<?php endif; ?>
		</div>
		<?= $content ?>
	</div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
