<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Вход');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="contact-container">
	<div class="container">
		<div class="row vspace"><!-- "vspace" class is added to distinct this row -->
			<div class="col-sm-3"></div>
			<div class="col-sm-6">
				<?php if (Yii::$app->session->hasFlash('success')): ?>
				<div class="row">
					<div class="alert alert-success">
							<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
					</div>
				</div><!-- /.flash-success -->
				<?php endif; ?>
				<?php if (Yii::$app->session->hasFlash('error')): ?>
				<div class="row">
					<div class="alert alert-danger">
						<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
					</div>
				</div><!-- /.flash-success -->
				<?php endif; ?>
				<h4><?= $this->title; ?></h4>
				<?php $form = ActiveForm::begin([
					'options' => [
						'class' => 'contact-form', 
					],
						//'id'=>'login-form',
					]); ?>
					<?= $form->field($model, 'login', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Логин'),
							'class' => 'form-control',
						]]); 
					?>
					<?= $form->field($model, 'password', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Пароль'),
							'class' => 'form-control',
						]])->passwordInput(['maxlength' => 32]);; 
					?>
					<div class="restore-password">
						<a href="restore-password"><?= Yii::t('form', 'Забыли свой пароль?'); ?></a>
					</div>
					<div class="form-group">
						<?/*= $form->field($model, 'reCaptcha')->widget(
							common\widgets\captcha\ReCaptcha::className(),
							['siteKey' => '6LewalwUAAAAAMSCSFELvzndwU7ksXxtROy93oOR']
						);*/?>
					</div>
					<div class="form-group text-right">
						<?= Html::submitButton(Yii::t('form', 'Вход'), ['class' => 'btn btn-primary']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
			<div class="col-sm-3"></div>
		</div>
	</div>
</section>
