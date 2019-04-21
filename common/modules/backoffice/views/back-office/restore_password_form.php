<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('form', 'Восстановление пароля');
$this->params['breadcrumbs'][] = $this->title;
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
				<?php if ($access): ?>
				<?php $form = ActiveForm::begin([
					'options' => [
						'class' => 'contact-form',
					],
					'id' => 'restore-password-form',
					]); 
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
						]])->passwordInput(['maxlength' => 32]);
					?>
					<div class="form-group text-right">
						<?= Html::submitButton(Yii::t('form', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
					</div>
				<?php ActiveForm::end(); ?>
				<?php else: ?>
				<div class="row">
					<div class="col-sm-12">
						<div class="row">
							<div class="notice error">
								<span>
									<strong><?= Html::encode(Yii::t('form', 'Неправильный доступ!')); ?></strong>
								</span>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-sm-2"></div>
		</div>
	</div>
</section>
