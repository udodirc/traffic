<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contacts */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="contacts-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php 
		echo $form->field($model, 'field[]')
			->dropDownList(
			$field_list,
			[
				'prompt'=>Yii::t('form', 'Выберите тип поля'),
				'style'=> 'width:200px;'
			] // options
		);
    ?>
	<?= $form->field($model, 'name[]')->textInput() ?>
    <?= $form->field($model, 'desc[]')->textarea(['rows' => 6]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Create') : Yii::t('form', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
