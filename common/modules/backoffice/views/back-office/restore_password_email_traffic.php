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
						'id'=>'restore-password-email-form',
					]); ?>
					<?= $form->field($model, 'email', [
						'template' => '<div class="form-group">
							{input}{hint}{error}
						</div>',
						'inputOptions' => [
							'placeholder' => Yii::t('form', 'Email'),
							'class' => 'form-control',
						]]); 
					?>
					<div class="form-group text-right">
						<?= Html::submitButton(Yii::t('form', 'Выслать'), ['class' => 'btn btn-primary']) ?>
					</div>
				<div class="col-sm-3"></div>
			</div>
		</div>
	</div>
</section>
