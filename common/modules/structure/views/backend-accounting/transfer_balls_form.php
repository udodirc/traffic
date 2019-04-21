<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="activate-form">
	<div class="structure-head">
		<h1><?= Yii::t('form', 'Перенос баллов'); ?></h1>
	</div>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'sender_login')->textInput(['style'=>'width:200px;']) ?>
    <?= $form->field($model, 'receiver_login')->textInput(['style'=>'width:200px;']) ?>
    <?= $form->field($model, 'balls')->textInput(['style'=>'width:200px;']) ?>
    <div class="form-group">
		<?= Html::submitButton(Yii::t('form', 'Перенести'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
