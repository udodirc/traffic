<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
	'options' => [
		'class'=>'forms-sample',
	],
]); ?>
	<div class="form-group <?= isset($model->errors['title']) ? 'has-danger' : ''?>">
		<?= Html::activeLabel($model, 'title', [
			'label' => Yii::t('form', 'Заголовок').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
		]); ?>
		<?= Html::activeTextInput($model, 'title', ['class'=>'form-control form-control-danger']); ?>
		<?= Html::error($model, 'title', [
			'class'=>'error mt-2 text-danger'
		]); ?>
	</div>
	<div class="form-group <?= isset($model->errors['link']) ? 'has-danger' : ''?>">
		<?= Html::activeLabel($model, 'link', [
			'label' => Yii::t('form', 'Ссылка').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
		]); ?>
		<?= Html::activeTextInput($model, 'link', ['class'=>'form-control form-control-danger']); ?>
		<?= Html::error($model, 'link', [
			'class'=>'error mt-2 text-danger'
		]); ?>
	</div>
	<div class="form-group <?= isset($model->errors['text']) ? 'has-danger' : ''?>">
		<?= Html::activeLabel($model, 'text', [
			'label' => Yii::t('form', 'Текст').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
		]); ?>
		<?= Html::activeTextarea($model, 'text', ['class'=>'form-control form-control-danger']); ?>
		<?= Html::error($model, 'text', [
			'class'=>'error mt-2 text-danger'
		]); ?>
	</div>
	<div class="form-group <?= isset($model->errors['balls']) ? 'has-danger' : ''?>">
		<?= Html::activeLabel($model, 'balls', [
			'label' => Yii::t('form', 'Баллы').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
		]); ?>
		<?= Html::activeTextInput($model, 'balls', ['class'=>'form-control form-control-danger']); ?>
		<?= Html::error($model, 'balls', [
			'class'=>'error mt-2 text-danger'
		]); ?>
	</div>
<?= Html::activehiddenInput($model, 'partner_id', ['value'=>$id]); ?>
<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary mr-2']) ?>
<?php ActiveForm::end(); ?>