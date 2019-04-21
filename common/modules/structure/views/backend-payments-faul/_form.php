<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\PaymentsFaul */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-faul-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'matrix_id')->textInput() ?>

    <?= $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Create') : Yii::t('form', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
