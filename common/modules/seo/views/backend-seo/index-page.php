<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="index-page-form">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="notice error">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'meta_tags')->textArea(['rows' => '6']) ?>
    <?= $form->field($model, 'meta_keywords')->textArea(['rows' => '6']) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
