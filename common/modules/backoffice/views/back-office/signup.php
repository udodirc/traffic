<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
$sponsorData = (isset($sponsorData)) ? $sponsorData : null;
?>
<section class="contact-container">
	<div class="container">
		<div class="row vspace"><!-- "vspace" class is added to distinct this row -->
			<div class="col-sm-2"></div>
			<div class="col-sm-8">
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
						'id'=>'signup-form',
					]); ?>
					<?php if($sponsorData !== null): ?>
					<div class="form-group">
						<?= Yii::t('form', 'Ваш спонсор').'&nbsp;-&nbsp;'.$sponsorData->login; ?>
					</div>
					<?php endif; ?>
					<?= $form->field($model, 'login', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Логин'),
							'class' => 'form-control',
						]]); 
					?>
					<?= $form->field($model, 'first_name', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Имя'),
							'class' => 'form-control',
						]]); 
					?>
					<?= $form->field($model, 'last_name', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Фамилия'),
							'class' => 'form-control',
						]]); 
					?>
					<?= $form->field($model, 'email', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Email'),
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
					<?= $form->field($model, 're_password', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Повторить пароль'),
							'class' => 'form-control',
						]])->passwordInput(['maxlength' => 32]);; 
					?>
					<?= $form->field($model, 'sponsor_id')->hiddenInput(['value' => ($sponsorData !== null) ? $sponsorData->id : 1])->label(false) ?>
					<div class="form-group">
						<?= $form->field($model, 'reCaptcha')->widget(
							common\widgets\captcha\ReCaptcha::className(),
							['siteKey' => '6LdaboEUAAAAAMIP2HyOTrw6uR4WZqP05kVPp1rJ']
						); ?>
					</div>
					<div class="form-group text-right">
						<?= Html::submitButton(Yii::t('form', 'Регистрация'), ['class' => 'btn btn-primary']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
			<div class="col-sm-2"></div>
		</div>
	</div>
</section>
