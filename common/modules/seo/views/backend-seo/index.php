<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('form', 'Seo');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailing-form">
	<h1><?= Html::encode($this->title) ?></h1>
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
    <?php if (!is_null($model)): ?>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'meta_title')->textArea(['rows' => '6']) ?>
    <?= $form->field($model, 'meta_desc')->textArea(['rows' => '6']) ?>
    <?= $form->field($model, 'meta_keywords')->textArea(['rows' => '6']) ?>
    <?= $form->field($model, 'name')->hiddenInput(['value'=>'index'])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
