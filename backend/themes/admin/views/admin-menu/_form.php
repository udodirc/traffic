<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\AdminMenu;
use common\components\DropDownListHelper;

/* @var $this yii\web\View */
/* @var $model app\models\AdminMenu */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="admin-menu-form">
	<?php $form = ActiveForm::begin();?>
	<div class="selector">
	<?= DropDownListHelper::getDropDownList($form, $model, 'AdminMenu', 'parent_id', 'app', [
		'prompt'=>Yii::t('form', 'Выберите группу'),
		'style'=>'width:300px;'
	],
	['parent_id = :id', [':id'=>0]]); ?>
    </div>
    <div id="submenu_list" class="form-group"></div>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'css')->textInput(['maxlength' => 100]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
