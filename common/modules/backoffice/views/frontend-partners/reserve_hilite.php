<?php
use yii\helpers\Html;
use common\components\ContentHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="col-md-6">
                    <div class="card-body">
						<h4 class="card-title"><?= Html::encode($this->title); ?></h4>
						<?php $form = ActiveForm::begin([
							'id'=>'inline-validation',
							'options' => [
								'class'=>'form-horizontal form-stripe'
							],
						]); ?>
						<!--
						<div class="form-group">
							<?/*= Html::activeLabel($model, 'structure', [
								'class'=>'col-sm-3 control-label',
								'label' => Yii::t('form', 'Структура').'<span class="required">*</span>'
							]); ?>
							<div class="col-sm-9">
								<?= $form->field($model, 'structure')->dropDownList($structures_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;'])->label(false); ?>
								<?= Html::error($model, 'structure', [
									'class'=>'error'
								]); */?>
							</div>
						</div>
						-->
						<div class="form-group <?= isset($model->errors['matrix']) ? 'has-danger' : ''?>">
							<?= Html::activeLabel($model, 'matrix', [
								'label' => Yii::t('form', 'Матрица').' '.'('.Yii::t('form', 'это поле должно быть заполнено').')'
							]); ?>
							<?= $form->field($model, 'matrix')->dropDownList($matrices_list, ['class'=>'form-control form-control-danger', 'prompt'=>'Выбрать', 'style'=>'width:300px;'])->label(false); ?>
							<?= Html::error($model, 'matrix', [
								'class'=>'error mt-2 text-danger'
							]); ?>
							<?= $form->field($model, 'structure')->hiddenInput(['value' => $structure])->label(false); ?>
						</div>
						<div class="form-group <?= isset($model->errors['places_count']) ? 'has-danger' : ''?>">
							<?= Html::activeLabel($model, 'places_count', [
								'label' => Yii::t('form', 'Кол-во мест в матрице').' '.'('.Yii::t('form', 'это поле должно быть заполнено').')'
							]); ?>
							<?= $form->field($model, 'places_count')->textInput(['style'=>'width:300px;', 'placeholder'=>Yii::t('form', 'Введите количество мест')])->label(false); ?>
							<?= Html::error($model, 'places_count', [
								'class'=>'error mt-2 text-danger'
							]); ?>
						</div>
						<?= $form->field($model, 'partner_id')->hiddenInput(['value' => $partner_id])->label(false); ?>
						<?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'btn btn-primary mr-2']) ?>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
				<div class="col-md-6">
                    <div class="card-body">
						<h4 class="card-title"><?= Yii::t('form', 'Информация'); ?></h4>
						<?= (isset($content) && $content != null) ? Html::encode($content->content) : ''; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
