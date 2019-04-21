<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('form', 'Счетчики');
$this->params['breadcrumbs'][] = $this->title;

$model->liveinternet = Html::decode((isset($countersData['liveinternet'])) ? $countersData['liveinternet'] : '');
$model->yandex = Html::decode((isset($countersData['yandex'])) ? $countersData['yandex'] : '');
$model->google = Html::decode((isset($countersData['google'])) ? $countersData['google'] : '');
?>
<div class="counters-form">
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
	<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'liveinternet')->textarea(['rows' => 6]) ?>
		<?= $form->field($model, 'yandex')->textarea(['rows' => 6]) ?>
		<?= $form->field($model, 'google')->textarea(['rows' => 6]) ?>
		<div class="form-group">
			<?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'button-blue']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
