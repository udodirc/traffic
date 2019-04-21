<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="static-content-form">
    <?php 
    $form = ActiveForm::begin(); 
    $model->is_feedbacks_allowed = $isFeedbacksAllowed;
    $model->is_tickets_allowed = $isTicketsAllowed;
    ?>
	<?= $form->field($model, 'is_feedbacks_allowed', ['inputOptions' => []])->checkbox([
		'inline' => true,
		'enableLabel' => false,
	]); 
	?>
	<?= $form->field($model, 'is_tickets_allowed', ['inputOptions' => []])->checkbox([
		'inline' => true,
		'enableLabel' => false,
	]); 
	?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
