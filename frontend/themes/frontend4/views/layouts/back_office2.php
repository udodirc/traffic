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
			<div class="add-banner">
				<?/*= Html::img(Url::to('@backend_upload_banner_dir'.DIRECTORY_SEPARATOR.'banner.jpg'), ['height'=>60, 'width'=>468, 'alt'=>'Banner'])*/ ?>
			</div>
			<div class="login-block">
				<!-- ## Panel Content  -->
				<?php $form = ActiveForm::begin(['id' => 'login-form', 'action'=>'login', 'fieldConfig' => [
                        'template' => "{input}",
                        'options' => [
                            'tag'=>'span',
                            'class'=>'login-input',
                        ]
					]]); ?>
					<?= $form->field($model, 'login')->label(false) ?>
					<?= $form->field($model, 'password')->passwordInput()->label(false) ?>
					<?= Html::submitButton(Yii::t('menu', 'Вход'), ['name' => 'login-button']) ?>
				<?php ActiveForm::end(); ?>
				<!-- ## / Panel Content  -->
			<div class="sign-up">
              <a href="signup">Регистрация</a>
              <a href="restore-password">Забыли свой пароль?</a>
            </div>
          </div>
        </div>
      </div>
      <div class="header-menu">
        <div class="container">
          <ul>
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
          </ul>
        </div>
      </div>
    </header>
    <div class="content-block">
      <div class="container">
        <div class="content-wrap">
          <div class="sidebar-menu">
            <h2 class="title-h2">ЛЕВОЕ МЕНЮ</h2>
            <ul>
              <?php if(Yii::$app->user->identity === null): ?>
				<?= Menu::widget([
					'items' => $frontendMenu->getBackOfficeMenuList(2, true),
					'options' => [
						'class'=>"nav-user-options"
					],
					'activeCssClass'=>false,
					'encodeLabels' => false,
					'linkTemplate'=>'<a href="{url}">{label}</a>',
				]); ?>
			<?php endif; ?>
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
        <div class="logo">
          <a href="index.html"></a>
        </div>
        <div class="footer-menu">
			<?php if(Yii::$app->user->identity === null): ?>
				<?= Menu::widget([
					'items' => $frontendMenu->getBackOfficeMenuList(3, true),
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
					'items' => $frontendMenu->getBackOfficeMenuList(3, true),
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
					'items' => $frontendMenu->getBackOfficeMenuList(3, true),
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
