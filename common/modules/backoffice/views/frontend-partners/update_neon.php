<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
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
				],
				]); ?>
				<div class="form-group">
					<?= Html::activeLabel($model, 'login', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Логин').'<span class="required">*</span>'
					]); ?>
					<div class="col-sm-9">
						<?= $model->login ?>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 'email', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Email').'<span class="required">*</span>'
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeTextInput($model, 'email', ['class'=>'form-control']); ?>
						<?= Html::error($model, 'email', [
							'class'=>'error'
						]); ?>
					</div>
				</div>
                <div class="form-group">
					<?= Html::activeLabel($model, 'first_name', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Имя').'<span class="required">*</span>'
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeTextInput($model, 'first_name', ['class'=>'form-control']); ?>
						<?= Html::error($model, 'first_name', [
							'class'=>'error'
						]); ?>
					</div>
				</div>
                <div class="form-group">
					<?= Html::activeLabel($model, 'last_name', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Фамилия').'<span class="required">*</span>'
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeTextInput($model, 'last_name', ['class'=>'form-control']); ?>
						<?= Html::error($model, 'last_name', [
							'class'=>'error'
						]); ?>
                    </div>
				</div>
                <div class="form-group">
					<?= Html::activeLabel($model, 'mailing', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Рассылка')
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeCheckbox($model, 'mailing', [
							'class'=>'form-control',
							'inline' => true,
							'label' => false
						]); ?>
					</div>
				</div>
                <div class="form-group">
					<?= Html::activeLabel($model, 'payeer_wallet', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Payeer кошелек')
					]); ?>
					<div class="col-sm-9">
						<?= Html::activeTextInput($model, 'payeer_wallet', ['class'=>'form-control']); ?>
						<?= Html::error($model, 'payeer_wallet', [
							'class'=>'error'
						]); ?>
                    </div>
				</div>
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
						<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => 'btn btn-success']) ?>
					</div>
                </div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
