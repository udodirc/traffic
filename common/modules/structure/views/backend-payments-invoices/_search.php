<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\PaymentsInvoicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-invoices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'partner_id') ?>

    <?= $form->field($model, 'matrix_number') ?>

    <?= $form->field($model, 'matrix_id') ?>

    <?= $form->field($model, 'paid_matrix_partner_id') ?>

    <?php // echo $form->field($model, 'paid_matrix_id') ?>

    <?php // echo $form->field($model, 'payment_type') ?>

    <?php // echo $form->field($model, 'account_type') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'transact_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('form', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
