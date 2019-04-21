<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Service;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$this->title = Yii::t('form', 'Редактирование: ', [
    'modelClass' => 'Menu',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="menu-update">
    <h1><?= Html::encode($this->title) ?></h1>
	<div class="menu-form">
	<?php $form = ActiveForm::begin(); ?>
		<div class="form-group">
			<?= Html::activeLabel($model,'name', ['label'=>Yii::t('form', 'Родительское меню')]); //label ?>
			<?= ($model->menu_name != '') ? $model->menu_name : Yii::t('form', 'Меню нет'); ?>
		</div>
		<div class="form-group">
			<?= Html::activeLabel($model,'controller', ['label'=>Yii::t('form', 'Контроллер')]); //label ?>
			<?= (Service::getControllerNameByID($model->controller_id) != '') ? Service::getControllerNameByID($model->controller_id) : Yii::t('form', 'Контроллера нет'); ?>
		</div>
		<div class="form-group">
			<?= ($model->content_name !== '') ? $model->content_name : Yii::t('form', 'Меню нет'); ?>
		</div>
		<div id="controller_data" class="form-group"></div>
		<?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
		<?= $form->field($model, 'url')->textInput(['maxlength' => 100]) ?>
		<?=$form->field($model, 'backoffice')->checkbox([
			'inline' => true,
			'enableLabel' => false, 
			'checked' => ($model->backoffice > 0) ? true : false,
		]);?>
		<?= $form->field($model, 'id')->hiddenInput(['value'=>$id])->label(false) ?>
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
		<div id="controller_data" class="form-group"></div>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
