<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Вывод денег');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-info">
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
    <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?= Html::encode($this->title); ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<?php $form = ActiveForm::begin(); ?>
					<fieldset>
						<?= $form->field($model, 'count') ?>
						<div class="form-group">
							<?= Html::submitButton(Yii::t('menu', 'Запрос'), ['class' => 'btn btn-success']) ?>
						</div>
					</fieldset>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
