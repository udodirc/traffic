<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="companies-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php if($update):?>
		<?= Yii::t('form', 'Матрица').' - '.$model->matrix?>
	<?php else:?>
		<?= $form->field($model, 'matrix')->textInput() ?>
    <?php endif;?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
