<?php
use frontend\assets\FrontendLandXAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Menu as MenuWidget;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use common\models\Menu as FrontendMenu;
use common\modules\seo\widgets\CounterWidget;

/* @var $this \yii\web\View */
/* @var $content string */

FrontendLandXAppAsset::register($this);
$bundle = FrontendLandXAppAsset::register($this);
$brandSlogan = (isset($this->params['brand_slogan'])) ? $this->params['brand_slogan'] : '';

$loginModel = (isset($this->params['loginModel'])) ? $this->params['loginModel'] : null;
$signupModel = (isset($this->params['signupModel'])) ? $this->params['signupModel'] : null;
$restorePasswordEmailModel = (isset($this->params['restorePasswordEmailModel'])) ? $this->params['restorePasswordEmailModel'] : null;
$sponsorData = (isset($this->params['sponsorData'])) ? $this->params['sponsorData'] : null;
$solution = (isset($this->params['solution'])) ? $this->params['solution'] : null;

if(\Yii::$app->session->hasFlash('confirm-registration') || \Yii::$app->session->hasFlash('restore-password'))
{
	if(\Yii::$app->session->hasFlash('confirm-registration'))
	{
		$inlineScript = '$(document).ready(function()
		{
			$("#confirm-registration").modal("show");
		});';
	}
	elseif(\Yii::$app->session->hasFlash('restore-password'))
	{
		$inlineScript = '$(document).ready(function()
		{
			$("#restore-password").modal("show");
		});';
	}
	
	$this->registerJs($inlineScript,  View::POS_END);
}
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
		<!-- =========================
			HEADER   
		============================== -->
		<header id="home">
			<!-- COLOR OVER IMAGE -->
			<div class="color-overlay">
				<div class="navigation-header">
					<!-- STICKY NAVIGATION -->
					<div class="navbar navbar-inverse bs-docs-nav navbar-fixed-top sticky-navigation">
						<div class="container">
							<div class="navbar-header">
								<!-- LOGO ON STICKY NAV BAR -->
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#landx-navigation">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<?/*= Html::a(Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'logo-dark.png'), ['alt'=>'logo', 'title'=>'logo']), '/', ['class'=>'navbar-brand']); */?>
							</div>
							<!-- NAVIGATION LINKS -->
							<div class="navbar-collapse collapse" id="landx-navigation">
								<div class="navbar-right signup">
									<?= Html::a('Вход', '#', ['id'=>'login-link']); ?>
									<?= Html::a('Регистрация', '#', ['id'=>'signup-link']); ?>
								</div>
								<!-- #menu -->
								<?= MenuWidget::widget([
									'items' => FrontendMenu::getMenuList(2),
									'options' => [
										'class'=>'nav navbar-nav navbar-right main-navigation',
									]
								]);
								?>
								<!-- /#menu -->
							</div>
						</div>
						<!-- /END CONTAINER -->
					</div>
					<!-- /END STICKY NAVIGATION -->
					<!-- ONLY LOGO ON HEADER -->
					<div class="navbar non-sticky">
						<div class="container">
							<div class="navbar-header">
								<img src="images/logo.png" alt="">
							</div>
							<ul class="nav navbar-nav navbar-right social-navigation hidden-xs">
								<li><a href="#"><i class="social_facebook_circle"></i></a></li>
								<li><a href="#"><i class="social_twitter_circle"></i></a></li>
								<li><a href="#"><i class="social_linkedin_circle"></i></a></li>
							</ul>
						</div>
						<!-- /END CONTAINER -->
			
					</div>
					<!-- /END ONLY LOGO ON HEADER -->
				</div>
				<!-- HEADING, FEATURES AND REGISTRATION FORM CONTAINER -->
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<!-- SCREENSHOT -->
							<div class="home-screenshot side-screenshot pull-left">
								<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'screenshots'.DIRECTORY_SEPARATOR.'1.jpg'), ['class'=>'img-responsive', 'alt'=>'Feature', 'title'=>'Feature']); ?>
							</div>
						</div>
						<!-- RIGHT - HEADING AND TEXTS -->
						<?php if($solution !== null): ?>
							<?= $solution; ?>
						<?php endif; ?>
					</div>
				</div>
				<!-- /END HEADING, FEATURES AND REGISTRATION FORM CONTAINER -->
			</div>
		</header>
		<?= $content; ?>
		<!-- =========================
			 SECTION FOOTER 
		============================== -->
		<footer class="bgcolor-2">
			<div class="container">
				<div class="footer-logo">
					<?/*= Html::a(Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'logo-dark.png'), ['alt'=>'logo', 'title'=>'logo']), '/', []); */?>
				</div>
				<div class="copyright">
					 ©<?= date("Y"); ?> <?= Yii::$app->name; ?>.
				</div>
				<!--
				<ul class="social-icons">
					<li><a href=""><span class="social_facebook_square"></span></a></li>
					<li><a href=""><span class="social_twitter_square"></span></a></li>
					<li><a href=""><span class="social_pinterest_square"></span></a></li>
					<li><a href=""><span class="social_googleplus_square"></span></a></li>
					<li><a href=""><span class="social_instagram_square"></span></a></li>
					<li><a href=""><span class="social_linkedin_square"></span></a></li>
				</ul>
				-->
			</div>
		</footer>
		<?php if($loginModel !== null): ?>
			<?php
			Modal::begin([
				'header' => '<h4>'.Yii::t('form', 'Вход').'</h4>',
				'id' => 'login-modal',
				'size' => 'modal-lg',
			]);
			?>
			<!-- EXPANDED LOGIN FORM -->
			<div class="expanded-contact-form">
			<!-- FORM -->
			<?php $form = ActiveForm::begin([
			'options' => [
				'class'=>'contact-form',
				'id'=>'login-form',
				'role'=>'form"',
			],
			'action'=>'login',
			]); 
			?>
				<!-- IF LOG SUCCESSFULLY -->
				<h6 class="success">
					<span class="colored-text icon_check"></span>
				</h6>
				<!-- IF LOG UNSUCCESSFULL -->
				<h6 class="error"></h6>
				<div class="field-wrapper col-md-6">
					<?= Html::activeTextInput($loginModel, 'login', ['id'=>'cf-login', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Ваш логин')]); ?>
				</div>
				<div class="field-wrapper col-md-6">
					<?= Html::activePasswordInput($loginModel, 'password', ['id'=>'cf-password', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Пароль')]); ?>
					<?= Html::error($loginModel, 'password', []); ?>
				</div>
				<div class="restore-password">
					<a id="restore-password-link" href="#"><?= Yii::t('form', 'Забыли свой пароль?'); ?></a>
				</div>
				<div class="field-wrapper col-md-12">
					<?= $form->field($loginModel, 'reCaptcha')->widget(
						common\widgets\captcha\ReCaptcha::className(),
						['siteKey' => '6LeiwJ8UAAAAADcw3ymj25xEht39C_nVMloTA84f']
					); ?>
				</div>
				<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn standard-button', 'id'=>'login-submit', 'data-style'=>'expand-left']) ?>
			<?php ActiveForm::end(); ?>
			<!-- /END FORM -->
			</div>
			<?php
			Modal::end();
		?>
		<?php endif; ?>
		<?php if($signupModel !== null): ?>
			<?php
			Modal::begin([
				'header' => '<h4>'.Yii::t('form', 'Регистрация').'</h4>',
				'id' => 'signup-modal',
				'size' => 'modal-lg',
			]);
			?>
			<!-- EXPANDED LOGIN FORM -->
			<div class="expanded-contact-form">
			<!-- FORM -->
			<?php $form = ActiveForm::begin([
			'options' => [
				'class'=>'contact-form',
				'id'=>'signup-form',
				'role'=>'form"',
			],
			'action'=>'signup',
			]); 
			?>
				<!-- IF LOG SUCCESSFULLY -->
				<h6 class="success">
					<span class="colored-text icon_check"></span> 
				</h6>
				<!-- IF LOG UNSUCCESSFULL -->
				<h6 class="error"></h6>
				<div class="field-wrapper col-md-6">
					<?= Html::activeTextInput($signupModel, 'login', ['id'=>'cf-login', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Ваш логин')]); ?>
				</div>
				<div class="field-wrapper col-md-6">
					<?= Html::activeTextInput($signupModel, 'email', ['id'=>'cf-email', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Email')]); ?>
				</div>
				<div class="field-wrapper col-md-6">
					<?= Html::activeTextInput($signupModel, 'first_name', ['id'=>'cf-first-name', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Имя')]); ?>
				</div>
				<div class="field-wrapper col-md-6">
					<?= Html::activeTextInput($signupModel, 'last_name', ['id'=>'cf-last-name', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Фамилия')]); ?>
				</div>
				<div class="field-wrapper col-md-6">
					<?= Html::activePasswordInput($signupModel, 'password', ['id'=>'cf-password', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Пароль')]); ?>
					<?= Html::error($signupModel, 'password', []); ?>
				</div>
				<div class="field-wrapper col-md-6">
					<?= Html::activePasswordInput($signupModel, 're_password', ['id'=>'cf-password', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Повторите ваш пароль')]); ?>
					<?= Html::error($signupModel, 're_password', []); ?>
				</div>
				<div class="field-wrapper col-md-12">
					<?= $form->field($signupModel, 'sponsor_id')->hiddenInput(['value' => ($sponsorData !== null) ? $sponsorData->id : 1])->label(false) ?>
					<?= $form->field($signupModel, 'reCaptcha')->widget(
						common\widgets\captcha\ReCaptcha::className(),
						['siteKey' => '6LeiwJ8UAAAAADcw3ymj25xEht39C_nVMloTA84f']
					); ?>
				</div>
				<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn standard-button', 'id'=>'login-submit', 'data-style'=>'expand-left']) ?>
			<?php ActiveForm::end(); ?>
			<!-- /END FORM -->
			</div>
			<?php
			Modal::end();
		?>
		<?php
			Modal::begin([
				'header' => '<h4>'.Yii::t('form', 'Подтверждение регистрации!').'</h4>',
				'id' => 'signup-success-modal',
				'size' => 'modal-lg',
			]);
			?>
			<!-- SUCCESS SIGNUP -->
			<div class="expanded-contact-form">
				<h6 class="success">
					<span class="colored-text icon_check"></span><?= Yii::t('messages', 'Вы успешно зарегестрированы, подтверждение регистрации отправлено на почту'); ?>
				</h6>
			</div>
			<?php
			Modal::end();
		?>
		<?php endif; ?>
		<?php if(\Yii::$app->session->hasFlash('confirm-registration')): ?>
			<?php
				Modal::begin([
					'header' => '<h4>'.Yii::t('form', 'Подтверждение регистрации!').'</h4>',
					'id' => 'confirm-registration',
					'size' => 'modal-lg',
				]);
				?>
				<!-- EXPANDED LOGIN FORM -->
				<div class="expanded-contact-form">
					<h6 class="success">
						<span class="colored-text icon_check"></span><?= Html::encode(Yii::$app->session->getFlash('success')); ?>
					</h6>
				</div>
				<?php
				Modal::end();
			?>
		<?php endif; ?>
		<?php if(\Yii::$app->session->hasFlash('restore-password')): ?>
			<?php
				Modal::begin([
					'header' => '<h4>'.Yii::t('form', 'Восстановление пароля!').'</h4>',
					'id' => 'restore-password',
					'size' => 'modal-lg',
				]);
				?>
				<!-- EXPANDED LOGIN FORM -->
				<div class="expanded-contact-form">
					<h6 class="success">
						<span class="colored-text icon_check"></span><?= Html::encode(Yii::$app->session->getFlash('restore-password')); ?>
					</h6>
				</div>
				<?php
				Modal::end();
			?>
		<?php endif; ?>
		<?php if($restorePasswordEmailModel !== null): ?>
			<?php
			Modal::begin([
				'header' => '<h4>'.Yii::t('form', 'Вход').'</h4>',
				'id' => 'restore-password-modal',
				'size' => 'modal-lg',
			]);
			?>
			<!-- EXPANDED LOGIN FORM -->
			<div class="expanded-contact-form">
			<!-- FORM -->
			<?php $form = ActiveForm::begin([
			'options' => [
				'class'=>'contact-form',
				'id'=>'restore-password',
				'role'=>'form"',
			],
			'action'=>'restore-password',
			]); 
			?>
				<!-- IF LOG SUCCESSFULLY -->
				<h6 class="success">
					<span class="colored-text icon_check"></span>
				</h6>
				<!-- IF LOG UNSUCCESSFULL -->
				<h6 class="error"></h6>
				<div class="field-wrapper col-md-12">
					<?= Html::activeTextInput($restorePasswordEmailModel, 'email', ['id'=>'cf-email', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Email')]); ?>
				</div>
				<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn standard-button', 'id'=>'login-submit', 'data-style'=>'expand-left']) ?>
			<?php ActiveForm::end(); ?>
			<!-- /END FORM -->
			</div>
			<?php
			Modal::end();
		?>
		<?php endif; ?>
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
