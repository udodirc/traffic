<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Yii::$app->request->baseUrl.'css/plugins/iCheck/custom.css',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',['depends' => [\yii\web\JqueryAsset::className()]]);
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
					<?= Html::activeLabel($model, 'email', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Логин').'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<?= $model->login ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 'email', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Email').'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">@</span>
							<?= Html::activeTextInput($model, 'email', ['class'=>'form-control', 'placeholder'=>'Email']); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 'email', []); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 'first_name', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Имя').'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">
								<i class="fa fa-user-o" aria-hidden="true"></i>
							</span>
							<?= Html::activeTextInput($model, 'first_name', ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Имя')]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 'first_name', []); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 'last_name', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Фамилия').'*'
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">
								<i class="fa fa-user-o" aria-hidden="true"></i>
							</span>
							<?= Html::activeTextInput($model, 'last_name', ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Фамилия')]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 'last_name', []); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 'mailing', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Рассылка')
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<?= Html::activeCheckbox($model, 'mailing', [
								'class'=>'form-control',
								'inline' => true,
								'label' => false
							]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 'mailing', []); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeLabel($model, 'blockchain', [
						'class'=>'col-sm-2 control-label',
						'label' => Yii::t('form', 'Bitcoin кошелек')
					]); ?>
					<div class="col-sm-10">
						<div class="input-group m-b">
							<span class="input-group-addon">
								<i class="fa fa-credit-card" aria-hidden="true"></i>
							</span>
							<?= Html::activeTextInput($model, 'blockchain', ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Bitcoin кошелек')]); ?>
						</div>
						<div class="help-block with-errors">
							<?= Html::error($model, 'blockchain', []); ?>
						</div>
					</div>
				</div>
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
