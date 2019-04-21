<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= Html::encode($this->title); ?></h4>
				<?php $form = ActiveForm::begin([
				'options' => [
					'class'=>'forms-sample',
				],
				]); ?>
				<div class="form-group <?= isset($model->errors['password']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 'password', [
						'label' => Yii::t('form', 'Пароль')
					]); ?>
					<?= Html::activePasswordInput($model, 'password', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 'password', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
				<div class="form-group <?= isset($model->errors['re_password']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 're_password', [
						'label' => Yii::t('form', 'Повторить пароль')
					]); ?>
					<?= Html::activePasswordInput($model, 're_password', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 're_password', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
				<?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary mr-2']) ?>
                <?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
