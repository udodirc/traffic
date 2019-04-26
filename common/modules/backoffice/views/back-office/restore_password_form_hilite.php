<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('form', 'Восстановление пароля');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="contact-us" id="contacts" style="margin-bottom:60px;">
	<div class="container">	
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<?php if ($access): ?>
				<h3 class="heading"><?= $this->title; ?></h3>
				<div class="expanded-contact-form">
					<!-- FORM -->
					<?php $form = ActiveForm::begin([
					'options' => [
						'class'=>'contact-form',
						'id'=>'restore-password-form',
						'role'=>'form"',
					],
					]); 
					?>
						<div class="field-wrapper col-md-6">
							<?= Html::activePasswordInput($model, 'password', ['id'=>'cf-password', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Пароль')]); ?>
							<?= Html::error($model, 'password', []); ?>
						</div>
						<div class="field-wrapper col-md-6">
							<?= Html::activePasswordInput($model, 're_password', ['id'=>'cf-password', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Повторите ваш пароль')]); ?>
							<?= Html::error($model, 're_password', []); ?>
						</div>
						<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn standard-button', 'id'=>'contacts-submit', 'data-style'=>'expand-left']) ?>
					<?php ActiveForm::end(); ?>
					<!-- /END FORM -->
				</div>
				<?php else: ?>
				<div class="notice error">
					<span>
						<strong><?= Html::encode(Yii::t('form', 'Неправильный доступ!')); ?></strong>
					</span>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
