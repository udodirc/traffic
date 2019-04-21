<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= Html::encode($this->title); ?></h4>
				<?php $form = ActiveForm::begin([
				'options' => [
					'class'=>'forms-sample',
				],
				]); ?>
				<div class="form-group">
					<?= Html::activeLabel($model, 'login', [
						'label' => Yii::t('form', 'Логин')
					]); 
					?>
					<?= Html::encode($model->login) ?>
				</div>
				<div class="form-group <?= isset($model->errors['email']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 'email', [
						'label' => Yii::t('form', 'Email').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
					]); ?>
					<?= Html::activeTextInput($model, 'email', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 'email', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
                <div class="form-group <?= isset($model->errors['first_name']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 'first_name', [
						'label' => Yii::t('form', 'Имя').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
					]); ?>
					<?= Html::activeTextInput($model, 'first_name', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 'first_name', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
				<div class="form-group <?= isset($model->errors['last_name']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 'last_name', [
						'label' => Yii::t('form', 'Фамилия').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
					]); ?>
					<?= Html::activeTextInput($model, 'last_name', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 'last_name', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
				<div class="form-check form-check-flat form-check-primary">
					<label class="form-check-label">
						<?= Html::activeCheckbox($model, 'mailing', [
							'class'=>'form-check-input',
							'inline' => true,
							'label' => false
						]); ?>
                        <?= Yii::t('form', 'Рассылка'); ?>
					</label>
				</div>
                <div class="form-group <?= isset($model->errors['payeer_wallet']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 'payeer_wallet', [
						'label' => Yii::t('form', 'Payeer кошелек').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
					]); ?>
					<?= Html::activeTextInput($model, 'payeer_wallet', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 'payeer_wallet', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
				<div class="form-group <?= isset($model->errors['password']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 'password', [
						'label' => Yii::t('form', 'Пароль')
					]); ?>
					<?= Html::activePasswordInput($model, 'password', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 'password', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
				<div class="form-group <?= isset($model->errors['re_password']) ? 'has-danger' : ''?>">
					<?= Html::activeLabel($model, 're_password', [
						'label' => Yii::t('form', 'Повторить пароль')
					]); ?>
					<?= Html::activePasswordInput($model, 're_password', ['class'=>'form-control form-control-danger']); ?>
					<?= Html::error($model, 're_password', [
						'class'=>'error mt-2 text-danger'
					]); ?>
				</div>
                <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary mr-2']) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
