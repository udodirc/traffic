<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-form">
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'sponsor_login')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'login')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'count')->textInput(['maxlength' => 100]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Создать'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
