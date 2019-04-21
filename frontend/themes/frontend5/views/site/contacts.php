<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Обратная связь');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="well8">
	<div class="container">
		<div class="title">
			<h4><?= $this->title; ?></h4>
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
		<div class="row offs2">
			<?php $form = ActiveForm::begin([
				'options' => [
					//'class' => 'mailform', 
				],
				'id' => 'message-form', 
				'action'=>'send-feedback',
			]); ?>
			<fieldset>
				<?= $form->field($model, 'name', [
				'template' => '<div class="col-md-6 col-sm-6 col-xs-12 col_mod">
					<label data-add-placeholder="">
						{input}{hint}{error}
					</label>
				</div>',
				'inputOptions' => [
					'placeholder' => Yii::t('form', 'Имя'),
					'class' => '',
				]]); ?>
				<?= $form->field($model, 'email', [
				'template' => '<div class="col-md-6 col-sm-6 col-xs-12 col_mod">
					<label data-add-placeholder="">
						{input}{hint}{error}
					</label>
				</div>',
				'inputOptions' => [
					'placeholder' => Yii::t('form', 'Email'),
					'class' => '',
				]]); ?>
				<?= $form->field($model, 'text', [
				'template' => '<div class="col-md-12 col-sm-12 col-xs-12 col_mod">
					<label data-add-placeholder="">
						{input}{hint}{error}
					</label>
				</div>',
				'inputOptions' => [
					'placeholder' => '',
					'class' => '',
				]])->textArea(['rows' => '6', 'id' => 'message_text']); ?>
				<div class="col-md-12 col-sm-12 col-xs-12 col_mod">
					<div class="mfControls">
						<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn-sm btn-sm__mod2 fa-arrow-circle-right', 'name' => 'login-button']) ?>
					</div>
				</div>
			</fieldset>	
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</section>
