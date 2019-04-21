<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\Withdrawal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="withdrawal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Create') : Yii::t('form', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
