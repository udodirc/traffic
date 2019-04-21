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
		<h1><?= Yii::t('form', 'Логин партнера').'&nbsp;-&nbsp;'.$partnerModel->login ?></h1>
	</div>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'structure')->dropDownList($structures_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
    <div class="form-group">
		<?= $form->field($model, 'pay')->hiddenInput(['value' => $pay])->label(false); ?>
		<?= $form->field($model, 'partner_id')->hiddenInput(['value' => $partner_id])->label(false); ?>
        <?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
