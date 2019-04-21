<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Permissions;
use common\models\Service;

/* @var $this yii\web\View */
/* @var $model app\models\Permissions */

$this->title = Yii::t('form', 'Редактирование: ', [
    'modelClass' => 'прав доступа',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="permissions-update">
	<div class="permissions-form">
	<?php $form = ActiveForm::begin();?>
		<?=$form->errorSummary($model);?>
		<div class="form-group">
			<?= Html::activeLabel($model,'menu_name', ['label'=>Yii::t('form', 'Группа')]); //label ?>
			<?= ($model->groups->name != '') ? $model->groups->name : Yii::t('form', 'Группы нет'); ?>
		</div>
		<div class="form-group">
			<?= Html::activeLabel($model,'controller', ['label'=>Yii::t('form', 'Контроллер')]); //label ?>
			<?= (Service::getControllerNameByID($model->controller_id) != '') ? Service::getControllerNameByID($model->controller_id) : Yii::t('form', 'Контроллера нет'); ?>
		</div>
		<?= $form->field($model, 'view_perm')->checkbox(); ?>
		<?= $form->field($model, 'create_perm')->checkbox(); ?>
		<?= $form->field($model, 'update_perm')->checkbox(); ?>
		<?= $form->field($model, 'delete_perm')->checkbox(); ?>
		<?= $form->field($model, 'id')->hiddenInput(['value'=>$id])->label(false) ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
		</div>
	<?php ActiveForm::end(); ?>
	</div>
</div>
