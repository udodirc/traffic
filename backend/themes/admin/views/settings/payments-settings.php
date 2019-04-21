<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StaticContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="static-content-form">
    <?php 
    $form = ActiveForm::begin(); 
    $model->is_activation_allowed = $isActivationAllowed;
    $model->is_payment_allowed = $isPaymentAllowed;
    ?>
	<?= $form->field($model, 'is_activation_allowed', ['inputOptions' => []])->checkbox([
		'inline' => true,
		'enableLabel' => false,
	]); 
	?>
	<?= $form->field($model, 'is_payment_allowed', ['inputOptions' => []])->checkbox([
		'inline' => true,
		'enableLabel' => false,
	]); 
	?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
