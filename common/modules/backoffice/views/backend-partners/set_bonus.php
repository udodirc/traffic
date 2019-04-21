<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="activate-form">
	<div class="structure-head">
		<h1><?= Yii::t('form', 'Назначить бонус'); ?></h1>
	</div>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'structure')->dropDownList($structures_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
    <div id="matrices_list" style="visibility: hidden">
		<div class="selector">
			<div class="form-group field-controller_id has-success">
				<label for="matrix" class="control-label"><?= Yii::t('form', 'Матрица') ?></label>
				<div class="help-block"></div>
			</div>    
		</div>
    </div>
    <?= $form->field($model, 'bonus')->dropDownList($bonuses_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
    <?= Html::hiddenInput('partner_id', $id, ['id'=>'partner_id']); ?>
    <?= Html::hiddenInput('partner_matrix', $matrix, ['id'=>'partner_matrix']); ?>
    <div class="form-group">
		<?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
