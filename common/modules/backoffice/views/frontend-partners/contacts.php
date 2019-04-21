<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="alert alert-success fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="alert alert-danger fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<div class="col-sm-8 col-md-8">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
			<?php $form = ActiveForm::begin([
					'id'=>'inline-validation',
					'options' => [
						'class'=>'form-horizontal form-stripe'
					],
					'action'=>'send-feedback'
				]); ?>
				<div class="form-group">
					<?= Html::activeLabel($model, 'name', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Имя')
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeTextInput($model, 'name', ['class'=>'form-control']); ?>
						<?= Html::error($model, 'name', [
							'class'=>'error'
						]); ?>
					</div>
                </div>
                <div class="form-group">
					<?= Html::activeLabel($model, 'email', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Email')
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeTextInput($model, 'email', ['class'=>'form-control']); ?>
						<?= Html::error($model, 'email', [
							'class'=>'error'
						]); ?>
					</div>
                </div>
                <div class="form-group">
					<?= Html::activeLabel($model, 'text', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Сообщение')
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeTextArea($model, 'text', ['cols' => '53', 'rows' => '6', 'id' => 'message_text']); ?>
						<?= Html::error($model, 'text', [
							'class'=>'error'
						]); ?>
					</div>
                </div>
                <div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-primary']) ?>
					</div>
				</div>
			<?php ActiveForm::end(); ?>     
			</div>
		</div>
	</div>
	<div class="col-sm-4 col-md-4">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
				<?= ($content !== null) ? $content->content : ''; ?>
			</div>
		</div>
	</div>
</div>
