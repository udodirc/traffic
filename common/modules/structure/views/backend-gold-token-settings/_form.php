<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\GoldTokenSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gold-token-settings-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
         <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Редактировать'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
