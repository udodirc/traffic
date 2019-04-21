<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('form', 'Редактирование партнера');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="partners-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="partners-form">
		<?php $form = ActiveForm::begin(); ?>
		<?= $model->login ?>
		<?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>
		<?= $form->field($model, 'first_name') ?>
		<?= $form->field($model, 'last_name') ?>
		<?= $form->field($model, 'password')->passwordInput(['maxlength' => 32]) ?>
		<?= $form->field($model, 're_password')->passwordInput(['maxlength' => 32]) ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
