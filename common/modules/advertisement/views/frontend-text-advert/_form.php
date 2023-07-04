<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
	'options' => [
		'class'=>'forms-sample',
	],
]); ?>
    <?= $form->errorSummary($model) ?>
	<div class="form-group <?= isset($model->errors['title']) ? 'has-danger' : ''?>">
		<?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(Yii::t('form', 'Заголовок').' ('.Yii::t('form', 'это поле должно быть заполнено')) ?>
	</div>
	<div class="form-group <?= isset($model->errors['link']) ? 'has-danger' : ''?>">
		<?= $form->field($model, 'link')->textInput(['maxlength' => true])->label(Yii::t('form', 'Ссылка').' ('.Yii::t('form', 'это поле должно быть заполнено, обязательно ввести https://').')') ?>
    </div>
    <div class="form-group <?= isset($model->errors['text']) ? 'has-danger' : ''?>">
		<?= $form->field($model, 'text')->textInput(['maxlength' => true])->label(Yii::t('form', 'Текст').' ('.Yii::t('form', 'это поле должно быть заполнено').')') ?>
    </div>
    <div class="form-group <?= isset($model->errors['text']) ? 'has-danger' : ''?>">
		<?= $form->field($model, 'balls')->textInput(['maxlength' => true])->label(Yii::t('form', 'Баллы').' ('.Yii::t('form', 'это поле должно быть заполнено').')') ?>
    </div>
<?= Html::activehiddenInput($model, 'partner_id', ['value'=>$partnerID]); ?>
<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary mr-2']) ?>
<?php ActiveForm::end(); ?>