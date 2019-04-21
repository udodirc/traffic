<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Вывод денег');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$paymentsTypes = (isset(Yii::$app->params['payments_types'])) ? Yii::$app->params['payments_types'] : [];
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="alert alert-success">
				<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
			</div>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="alert alert-danger">
				<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
			</div>
		</div>
	</div>
</div>
<!-- /.flash-error -->
<?php endif; ?>
<div class="row">
	<div class="col-lg-8">
		<div class="ibox-content">
			<?php $form = ActiveForm::begin([
				'options' => ['class'=>'form-horizontal'],
			]); 
			?>
				<div class="form-group">
					<?= Html::activeLabel($model, $paymentsTypes[$type][1], [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', $paymentsTypes[$type][0]).'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">
								<i class="fa fa-credit-card" aria-hidden="true"></i>
							</span>
							<?= Html::activeTextInput($model, $paymentsTypes[$type][1], ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Номер кошелька')]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, $paymentsTypes[$type][1], []); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 'withdrawal_amount', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Сумма вывода денег').'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">
								<i class="fa fa-money"></i>
							</span>
							<?= Html::activeTextInput($model, 'withdrawal_amount', ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Сумма вывода денег')]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 'withdrawal_amount', []); ?>
						</div>
					</div>
				</div>
				<div class="hr-line-dashed"></div>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-2">
						<?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary']) ?>
                    </div>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Html::encode($this->title); ?></h5>
			</div>
            <div class="ibox-content">
				<div class="panel-body">
					<?= (isset($content) && $content != null) ? $content->content : ''; ?>
				</div>
			</div>
		</div>
	</div>
</div>
