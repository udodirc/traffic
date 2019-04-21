<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="row">
		<div class="panel-wrapper panel-login">
			<div class="panel">
				<div class="title">
					<h4>User Login</h4>
					<div class="option">Sign up for free &raquo;</div>
				</div>
				<div class="content">
					<!-- ## Panel Content  -->
					<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
					<?= $form->field($model, 'login') ?>
					<?= $form->field($model, 'password')->passwordInput() ?>
					<?= $form->field($model, 'rememberMe')->checkbox() ?>
					<div class="form-group">
						<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
					</div>
					<?php ActiveForm::end(); ?>
					<!-- ## / Panel Content  -->
				</div>
			</div>
			<div class="shadow"></div>
		</div>
	</div>	
</div>
