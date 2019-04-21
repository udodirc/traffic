<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= Html::encode($this->title); ?></h2>
<br/>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
					<?= Html::encode($this->title); ?>
				</div>
			</div>
			<div class="panel-body">
				<?php $form = ActiveForm::begin([
					'options' => [
						'class'=>'form-horizontal form-groups-bordered',
						'role'=>'form',
					],
				]); ?>
				<div class="form-group">
					<?= Html::activeLabel($model, 'password', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Пароль')
					]); ?>
					<div class="col-sm-5">
						<?= Html::activePasswordInput($model, 'password', ['class'=>'form-control']); ?>
						<?= Html::error($model, 'password', [
							'class'=>'validate-has-error'
						]); ?>
					</div>
				</div>
                <div class="form-group">
					<?= Html::activeLabel($model, 're_password', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Повторить пароль')
					]); ?>
					<div class="col-sm-5">
						<?= Html::activePasswordInput($model, 're_password', ['class'=>'form-control']); ?>
						<?= Html::error($model, 're_password', [
							'class'=>'validate-has-error'
						]); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'btn btn-success']) ?>
					</div>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
