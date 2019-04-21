<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use vova07\imperavi\Widget as Redactor;
?>
<!-- Основная часть -->
<div class="add_ad_form">
	<?php $form = ActiveForm::begin([
		'id' => 'add-advert-text-form',
		/*'fieldConfig' => [
			'template' => "{input}",
            'options' => [
				'tag'=>'span'
			]
		]*/
	]); 
	?>
	<div class="line">
		<div class="name left"><?= Yii::t('form', 'Заголовок'); ?>:</div>
		<div class="input_box left">
			 <?= $form->field($model, 'title')->textInput(['maxlength' => 100, 'style'=>'width:700px;'])->label(false) ?>
		</div>
	</div>
	<div class="line">
		<div class="name left"><?= Yii::t('form', 'Ссылка'); ?>:</div>
		<div class="input_box left">
			 <?= $form->field($model, 'link')->textInput(['maxlength' => 100, 'style'=>'width:700px;'])->label(false) ?>
		</div>
	</div>
	<div class="line">
		<div class="name left"><?= Yii::t('form', 'Баллы'); ?>:</div>
		<div class="input_box left">
			 <?= $form->field($model, 'balls')->textInput(['maxlength' => 100, 'style'=>'width:100px;'])->label(false) ?>
		</div>
	</div>
	<div class="line">
		<div class="name left"><?= Yii::t('form', 'Описание'); ?>:</div>
		<div class="input_box left">
			<?= $form->field($model, 'text')->textInput(['maxlength' => 100, 'style'=>'width:700px;'])->label(false) ?>
		</div>
	</div>
	<div class="line">
		<div class="input_box left">
			<div class="submit_btn_box bottom">
			<? echo $form->field($model, 'partner_id')->hiddenInput(['value' => $partner_id])->label(false); ?>
			<?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'submit_btn']) ?>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
<!-- End Основная часть -->
