<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Вход');
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
						<div class="col-sm-6 b-r"><h3 class="m-t-none m-b"><?= $this->title; ?></h3>
							<?php $form = ActiveForm::begin([
								'options' => [
									'role' => 'form', 
								]
							]); ?>
								<?= $form->field($model, 'login', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
									'placeholder' => Yii::t('form', 'Логин'),
									'class' => 'form-control',
								]]); ?>
								<?= $form->field($model, 'password', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Пароль'),
										'class' => 'form-control',
								]])->passwordInput(['maxlength' => 32]); ?>
								<div class="col-sm-6 b-r">
									<div class="form-group">
										<?= $form->field($model, 'reCaptcha')->widget(
											common\widgets\captcha\ReCaptcha::className(),
											['siteKey' => '6LdQjRMUAAAAAECdoEwiGZwBKmLeMGMmZHNMBdRf']
										); ?>
									</div>
								</div>
								<div class="col-sm-6 b-r">
									<div class="form-group">
										<?= Html::submitButton(Yii::t('form', 'Вход'), ['class' => 'btn btn-sm btn-primary pull-right m-t-n-xs']) ?>
									</div>
								</div>
							<?php ActiveForm::end(); ?>
                        </div>
                        <div class="col-sm-6"><h4><?= Yii::t('form', 'Еще не зарегистрированы?'); ?></h4>
							<p><?= Yii::t('form', 'Вы можете зарегистрироваться здесь'); ?>:</p>
							<p class="text-center">
								<a href="signup"><i class="fa fa-sign-in big-icon"></i></a>
                            </p>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>
