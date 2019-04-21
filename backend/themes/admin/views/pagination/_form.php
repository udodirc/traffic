<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\AdminMenu;
use common\components\DropDownListHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Pagination */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pagination-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= DropDownListHelper::getDropDownList($form, $model, 'AdminMenu', 'menu_id', 'app', [
		'prompt'=>Yii::t('form', 'Выберите группу'),
		'style'=>'width:300px;'
	],
	['parent_id > :id', [':id' => 0]]); ?>
    <?= $form->field($model, 'value')->textInput(['style'=> 'width:50px;']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
