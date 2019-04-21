<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="reserve-form">
	<div class="structure-head">
		<h1><?= Yii::t('form', 'Форма фильтров'); ?></h1>
	</div>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'country')->dropDownList($country_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
    <?= $form->field($model, 'status')->dropDownList($statuses_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
    <?= $form->field($model, 'top_leaders_count')->textInput(['style'=>'width:200px;']) ?>
    <?= $form->field($model, 'partners_offset')->textInput(['style'=>'width:200px;']) ?>
    <?= $form->field($model, 'partners_count')->textInput(['style'=>'width:200px;']) ?>
    <?=$form->field($model, 'mailing')->checkbox([
		'inline' => true,
		'enableLabel' => false
	]);?>
	<?=$form->field($model, 'login')->checkbox([
		'inline' => true,
		'enableLabel' => false
	]);?>
	<?=$form->field($model, 'first_name')->checkbox([
		'inline' => true,
		'enableLabel' => false
	]);?>
	<?=$form->field($model, 'last_name')->checkbox([
		'inline' => true,
		'enableLabel' => false
	]);?>
    <div class="form-group">
		<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
