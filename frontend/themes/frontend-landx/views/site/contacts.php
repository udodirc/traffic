<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Контакты');
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
				<?php $form = ActiveForm::begin([
					'options' => [
						'class' => 'contact-form', 
					],
						'action'=>'send-feedback',
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
                    <?php if(isset(Yii::$app->params['captcha_site_key'])): ?>
                        <div class="form-group">
                            <?= $form->field($model, 'reCaptcha')->widget(
                                common\widgets\captcha\ReCaptcha::className(),
                                ['siteKey' => Yii::$app->params['captcha_site_key']]
                            ) ?>
                        </div>
                    <?php endif; ?>
					<div class="form-group text-right">
						<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-primary']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
			<div class="col-sm-2"></div>
		</div>
	</div>
</section>
