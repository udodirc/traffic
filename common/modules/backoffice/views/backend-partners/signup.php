<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;

$sponsorData = (isset($sponsorData)) ? $sponsorData : null;
?>
<div class="container">
	<div class="row">
		<div class="panel-wrapper panel-login backend"  style="margin-top: 100px;">
			<div class="panel" style="margin: 0;">
				<div class="title">
					<h4><?= Yii::t('menu', 'Регистрация') ?></h4>
				</div>
				<?php if (Yii::$app->session->hasFlash('success')): ?>
				<div class="notice success">
					<span>
						<strong><?= Html::encode(Yii::$app->session->getFlash('success')).' - '.Html::a(Yii::t('form', 'Личный кабинет'), Url::base(true).'/login', []); ?></strong>
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
					<?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>
					<?= $form->field($model, 'sponsor_login'); ?>
					<?= $form->field($model, 'login') ?>
					<?= $form->field($model, 'first_name') ?>
					<?= $form->field($model, 'last_name') ?>
					<?= $form->field($model, 'email') ?>
					<?= $form->field($model, 'password')->passwordInput(['maxlength' => 32]) ?>
					<?= $form->field($model, 're_password')->passwordInput(['maxlength' => 32]) ?>
					<div class="form-group">
						<?= Html::submitButton(Yii::t('menu', 'Регистрация'), ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
					</div>
					<?php ActiveForm::end(); ?>
					<!-- ## / Panel Content  -->
					<div class="shadow"></div>
				</div>
			</div>
		</div>
	</div>
</div>
