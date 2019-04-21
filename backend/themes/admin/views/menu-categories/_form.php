<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MenuCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-categories-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
