<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pagination */

$this->title = Yii::t('form', 'Редактирование: №', [
    'modelClass' => 'пагинации',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Пагинация'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="pagination-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="menu-form">
	<?php $form = ActiveForm::begin(); ?>
		<div class="form-group">
			<?= ($model->menu_name !== NULL) ? $model->menu_name : Yii::t('form', 'Меню нет'); ?>
		</div>
		<?= $form->field($model, 'value')->textInput(['maxlength' => 30]) ?>
		<?= $form->field($model, 'id')->hiddenInput(['value'=>$id])->label(false) ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
		</div>
	<?php ActiveForm::end(); ?>
	</div>
</div>
