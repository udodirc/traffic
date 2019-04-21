<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = $this->title;

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
	<div class="col-lg-12">
		<div class="ibox-content">
			<?php $form = ActiveForm::begin([
				'options' => ['class'=>'form-horizontal'],
			]); 
			?>
				<div class="form-group">
					<?= Html::activeLabel($model, 'password', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Пароль').'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
							<?= Html::activePasswordInput($model, 'password', ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Пароль')]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 'password', []); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 're_password', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Повторить пароль').'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
							<?= Html::activePasswordInput($model, 're_password', ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Повторить пароль')]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 're_password', []); ?>
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
</div>
