<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Вход');
$this->params['breadcrumbs'][] = $this->title;
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
						<?php $form = ActiveForm::begin([
							'options' => [
								//'class' => 'mailform', 
							],
							'id' => 'login-form',
						]); ?>
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<?= $form->field($model, 'login', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Логин'),
										'class' => '',
									]]); ?>
									<?= $form->field($model, 'password', [
									'template' => '<div class="col-sm-6">
										<div class="controls">
											{input}{hint}{error}
										</div>
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Пароль'),
										'class' => '',
									]])->passwordInput(['maxlength' => 32]); ?>
								</div>
							</div>
							<div class="restore-password">
								<a href="restore-password">Забыли свой пароль?</a>
							</div>
							<?/*= $form->field($model, 'reCaptcha')->widget(
								common\widgets\captcha\ReCaptcha::className(),
								['siteKey' => '6Le3szsUAAAAAOMdQNGpbgKVumgxkm9cLBs5XPqP']
							);*/?>
							<div class="col-md-12">
								<?= Html::submitButton(Yii::t('form', 'Вход'), ['class' => 'btn pull-right', 'name' => 'login-button']) ?>
							</div>
						</div>
						<?php ActiveForm::end(); ?>
					</main>
				</div>
			</div>
		</div>
	</div>
</div>
