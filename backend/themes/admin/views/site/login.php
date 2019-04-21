<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Вход');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="row">
		<div class="panel-wrapper panel-login"  style="margin-top: 100px;">
			<div class="panel" style="margin: 0;">
				<div class="title">
					<h4><?= $this->title; ?></h4>
				</div>
				<div class="content">
					<!-- ## Panel Content  -->
					<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
					<?= $form->field($model, 'login') ?>
					<?= $form->field($model, 'password')->passwordInput() ?>
					<div class="form-group">
						<?= Html::submitButton(Yii::t('menu', 'Вход'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
					</div>
					<?php ActiveForm::end(); ?>
					<!-- ## / Panel Content  -->
					<div class="shadow"></div>
				</div>
			</div>
		</div>
	</div>	
</div>
