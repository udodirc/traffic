<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Menu;
use common\components\DropDownListHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="selector">
    <?= DropDownListHelper::getDropDownList($form, $model, 'MenuCategories', 'category_id', 'app', [
		'prompt'=>Yii::t('form', 'Выберите категорию'),
		'style'=>'width:300px;'
	]); ?>
    </div>
    <div id="menu_list" style="visibility: hidden">
		<div class="selector">
			<div class="form-group field-menu_id has-success">
				<label for="menu_id" class="control-label">Подменю</label>
				<div class="help-block"></div>
			</div>    
		</div>
    </div>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'backoffice')->checkbox([
		'inline' => true,
		'enableLabel' => false
	]);?>
	<?php if(!empty($country_list)): ?>
	<div class="selector">
		<?= $form->field($model, 'iso')->dropDownList($country_list, [
			'prompt'=>Yii::t('form', 'Выбрать страну'),
			'style'=> 'width:300px;',
			'class'=>'form-control'
		]); 
		?>
	</div>
	<?php endif; ?>
	<?php if(!empty($status_list)): ?>
	<div class="selector">
		<?= $form->field($model, 'partner_status')->dropDownList($status_list, [
			'prompt'=>Yii::t('form', 'Выбрать статус'),
			'style'=> 'width:300px;',
			'class'=>'form-control'
		]); 
		?>
	</div>
    <?php endif; ?>
    <div class="selector">
	<?= DropDownListHelper::getControlersDropDownList($form, $model, 'controller_id', 'menu_controllers', 'name', [
		'prompt'=>Yii::t('form', 'Выберите контроллер'),
		'style'=>'width:300px;'
	]); ?>
    </div>
    <div id="controllers_list" style="visibility: hidden">
		<div class="selector">
			<div class="form-group field-controller_id has-success">
				<label for="controller_id" class="control-label"><?= Yii::t('form', 'Страница модуля') ?></label>
				<div class="help-block"></div>
			</div>    
		</div>
    </div>
    <div id="controllers_submenu_list" style="visibility: hidden">
		<div class="selector">
			<div class="form-group field-controller_id has-success">
				<label for="submen_controller_id" class="control-label"><?= Yii::t('form', 'Страница') ?></label>
				<div class="help-block"></div>
			</div>    
		</div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
