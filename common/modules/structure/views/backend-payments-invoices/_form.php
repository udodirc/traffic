<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\PaymentsInvoices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-invoices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'matrix_number')->textInput() ?>

    <?= $form->field($model, 'matrix_id')->textInput() ?>

    <?= $form->field($model, 'paid_matrix_partner_id')->textInput() ?>

    <?= $form->field($model, 'paid_matrix_id')->textInput() ?>

    <?= $form->field($model, 'payment_type')->textInput() ?>

    <?= $form->field($model, 'account_type')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transact_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Create') : Yii::t('form', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
