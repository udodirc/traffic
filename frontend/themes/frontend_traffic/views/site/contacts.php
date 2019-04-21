<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Контакты');
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="contacts" class="container form-section">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="alert alert-success">
					<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
				</div>
			</div>
		</div>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="alert alert-danger">
					<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- /.flash-error -->
	<?php endif; ?>	
    <div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-12"><h3 class="m-t-none m-b"><?= $this->title; ?></h3>
							<?php $form = ActiveForm::begin([
								'options' => [
									'role' => 'form', 
								]
							]); ?>
								<?= $form->field($model, 'name', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Имя'),
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
								<?= $form->field($model, 'text', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Сообщение'),
										'class' => 'form-control',
									]])->textArea(['rows' => '6', 'id' => 'message_text']);
								?>
								<div class="col-sm-12">
									<div class="form-group">
										<?/*= $form->field($model, 'reCaptcha')->widget(
											common\widgets\captcha\ReCaptcha::className(),
											['siteKey' => '6LdQjRMUAAAAAECdoEwiGZwBKmLeMGMmZHNMBdRf']
										); */?>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-sm btn-primary pull-right m-t-n-xs']) ?>
									</div>
								</div>
							<?php ActiveForm::end(); ?>
                        </div>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>
