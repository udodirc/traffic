<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\DropDownListHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Permissions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permissions-form">
	<?php $form = ActiveForm::begin(); ?>
	<div class="selector">
    <?= DropDownListHelper::getDropDownList($form, $model, 'UserGroups', 'group_id', 'app', [
		'prompt'=>Yii::t('form', 'Выберите группу'),
		'style'=>'width:300px;'
	]); ?>
    </div>
	<div class="selector">
	<?= DropDownListHelper::getControlersDropDownList($form, $model, 'controller_id', 'controllers', 'name', [
		'prompt'=>Yii::t('form', 'Выберите контроллер'),
		'style'=>'width:300px;'
	]); ?>
    </div>
    <?= $form->field($model, 'view_perm')->checkbox(); ?>
    <?= $form->field($model, 'create_perm')->checkbox(); ?>
	<?= $form->field($model, 'update_perm')->checkbox(); ?>
	<?= $form->field($model, 'delete_perm')->checkbox(); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
