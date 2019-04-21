<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-form">
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'module_id')->textInput(['maxlength' => 100]) ?>
    <div id="field-list"></div>
    <div class="form-group">
        <?= Html::a(Yii::t('form', 'Добавить поле'), null, $options = ['id'=>'add-field', 'class' => 'btn btn-success']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Редактировать'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
