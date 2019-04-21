<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\pages\models\Pages */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="pages-form">
    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
	<?= $form->field($model, 'url')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'body')->textarea(['rows' => 30]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Редактировать'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
   <?php ActiveForm::end(); ?>
</div>
