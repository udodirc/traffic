<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (isset($this->params['meta_title'])) ? $this->params['meta_title'] : 'Регистрация';
$this->registerMetaTag([
    'name' => 'description',
    'content' => (isset($this->params['meta_tags'])) ? $this->params['meta_tags'] : 'Регистрация|Без админа! "Всемирная касса взаимопомощи!"',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => (isset($this->params['meta_keywords'])) ? $this->params['meta_keywords'] : 'регистрация, без админа, withoutadmin, заработок в сети, заработок в интернете, новый проект, мгновенные денежные переводы, матрица, млм, mlm',
]);
$this->params['breadcrumbs'][] = $this->title;
$sponsorData = (isset($sponsorData)) ? $sponsorData : null;
?>
<div id="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="moduletable col-sm-12">
				<div class="module_container">
					<ul class="breadcrumb">
						<li class="active"><span><?= $this->title; ?></span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="content">
	<div class="container">
		<div class="row">
			<div class="content-inner">
				<div id="component" class="col-sm-12">
					<main role="main">
						<div class="page_header">
							<h2 class="heading-style-2 visible-first">
								<span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first"><?= $this->title; ?></span> 
							</h2>
						</div>
						<?php if (Yii::$app->session->hasFlash('success')): ?>
						<div class="notice success">
							<span>
								<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
							</span>
						</div><!-- /.flash-success -->
						<?php endif; ?>
						<?php if (Yii::$app->session->hasFlash('error')): ?>
						<div class="notice error">
							<span>
								<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
							</span>
						</div><!-- /.flash-success -->
						<?php endif; ?>
						<div class="title">
							<h6>
								<span style="margin: 10px 0;"><?= Yii::t('menu', 'ВНИМАНИЕ!<br/> 
								НЕ ИСПОЛЬЗУЙТЕ для Регистрации почту mail.ru, inbox.ru, list.ru, bk.ru, ua, ua.net  - так как вы не сможете подтвердить регистрацию!<br/>
								Пользуйтесь почтой gmail.com , yahoo.com<br/>') ?>
								</span>
							</h6>
						</div>
						<?php $form = ActiveForm::begin([
							'options' => [],
							'id' => 'signup-form',
						]); ?>
						<div class="row">
							<?php if($sponsorData !== null): ?>
							<div class="col-sm-12">
								<div class="row">
									<div class="sposnsor-login">
										<?= Yii::t('form', 'Ваш спонсор').'&nbsp;-&nbsp;'.$sponsorData->login; ?>
									</div>
								</div>
							</div>
							<?php endif; ?>
							<div class="col-sm-12">
								<div class="row">
									<?= $form->field($model, 'login', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Логин - Только латинские буквы, цифры'),
										'class' => '',
									]]); ?>
									<?= $form->field($model, 'first_name', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Имя'),
										'class' => '',
									]]); ?>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="row">
									<?= $form->field($model, 'last_name', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Фамилия'),
										'class' => '',
									]]); ?>
									<?= $form->field($model, 'email', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Email - Желательно gmail.com'),
										'class' => '',
									]]); ?>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="row">
									<?= $form->field($model, 'password', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Пароль - Только латинские буквы, цифры'),
										'class' => '',
									]])->passwordInput(['maxlength' => 32]); ?>
									<?= $form->field($model, 're_password', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Повторить пароль'),
										'class' => '',
									]])->passwordInput(['maxlength' => 32]); ?>
								</div>
							</div>
							<?= $form->field($model, 'sponsor_id')->hiddenInput(['value' => ($sponsorData !== null) ? $sponsorData->id : 1])->label(false) ?>
							<?= $form->field($model, 'reCaptcha')->widget(
								common\widgets\captcha\ReCaptcha::className(),
								['siteKey' => '6Le3szsUAAAAAOMdQNGpbgKVumgxkm9cLBs5XPqP']
							);?>
							<div class="col-md-12">
								<?= Html::submitButton(Yii::t('form', 'Регистрация'), ['class' => 'btn pull-right', 'name' => 'login-button']) ?>
							</div>
						</div>
						<?php ActiveForm::end(); ?>
					</main>
				</div>
			</div>
		</div>
	</div>
</div>
