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
<div class="activation">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="alert alert-success fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="alert alert-danger fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
	<div class="col-sm-6 col-md-6">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
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
				<div class="form-group">
					<?= Html::activeLabel($model, 'matrix', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Матрица').'<span class="required">*</span>'
					]); ?>
					<div class="col-sm-9">
						<?= $form->field($model, 'matrix')->dropDownList($matrices_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;'])->label(false); ?>
						<?= Html::error($model, 'matrix', [
							'class'=>'error'
						]); ?>
						<?= $form->field($model, 'structure')->hiddenInput(['value' => $structure])->label(false); ?>
					</div>
                </div>
                <div class="form-group">
					<?= Html::activeLabel($model, 'places_count', [
						'class'=>'col-sm-3 control-label',
						'label' => Yii::t('form', 'Кол-во мест в матрице').'<span class="required">*</span>'
					]); ?>
					<div class="col-sm-9">
						<?= $form->field($model, 'places_count')->textInput(['style'=>'width:200px;'])->label(false); ?>
						<?= Html::error($model, 'places_count', [
							'class'=>'error'
						]); ?>
					</div>
                </div>
                <div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<?= $form->field($model, 'partner_id')->hiddenInput(['value' => $partner_id])->label(false); ?>
						<?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'btn btn-primary']) ?>
                   </div>
               </div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>	
	</div>
	<div class="col-sm-6 col-md-6">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>      
			</div>
		</div>
	</div>
</div>
