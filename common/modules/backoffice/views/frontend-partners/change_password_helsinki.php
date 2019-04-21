<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-password">
	<div class="col-sm-12 col-md-12">
		<h4 class="section-subtitle"><?= Html::encode($this->title); ?></h4>
        <div class="panel">
			<div class="panel-content">
				<div class="row">
					<div class="col-md-12">
					<?php $form = ActiveForm::begin([
						'id'=>'inline-validation',
						'options' => [
							'class'=>'form-horizontal form-stripe'
						],
						]); ?>
						<div class="form-group">
							<?= Html::activeLabel($model, 'password', [
								'class'=>'col-sm-3 control-label',
								'label' => Yii::t('form', 'Пароль')
							]); ?>
							<div class="col-sm-9">
								<?= Html::activePasswordInput($model, 'password', ['class'=>'form-control']); ?>
								<?= Html::error($model, 'password', [
									'class'=>'error'
								]); ?>
                            </div>
                        </div>
                        <div class="form-group">
							<?= Html::activeLabel($model, 're_password', [
								'class'=>'col-sm-3 control-label',
								'label' => Yii::t('form', 'Повторить пароль')
							]); ?>
							<div class="col-sm-9">
								<?= Html::activePasswordInput($model, 're_password', ['class'=>'form-control']); ?>
								<?= Html::error($model, 're_password', [
									'class'=>'error'
								]); ?>
                            </div>
                        </div>
                        <div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary']); ?>
                            </div>
                        </div>
					<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
