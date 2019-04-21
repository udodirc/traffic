<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Обратная связь');
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<div class="row">
		<div class="panel-wrapper panel-login"  style="margin-top: 100px;">
			<div class="panel" style="margin: 0;">
				<div class="title">
					<h4><?= Yii::t('menu', 'Обратная связь') ?></h4>
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
				<div class="content">
					<!-- ## Panel Content  -->
					<?php $form = ActiveForm::begin(['id' => 'message-form', 'action'=>'send-feedback']); ?>
						<?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
						<?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>
						<?= $form->field($model, 'text')->textArea(['rows' => '6', 'id' => 'message_text'])->label(true) ?>
						<?= $form->field($model, 'reCaptcha')->widget(
							common\widgets\captcha\ReCaptcha::className(),
							['siteKey' => ((isset($this->params['captcha_site_key'])) ? $this->params['captcha_site_key'] : '')]
						) ?>
						<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn blue', 'id' => 'send_message']) ?>
					<?php ActiveForm::end(); ?>
					<!-- ## / Panel Content  -->
					<div class="shadow"></div>
				</div>
			</div>
		</div>
	</div>	
</div>
