<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\modules\structure\models\Matrix;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="reserve-form">
	<div class="structure-head">
		<h1><?= Yii::t('form', 'Логин партнера').'&nbsp;-&nbsp;'.$partnerModel->login ?></h1>
	</div>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'structure')->dropDownList($structures_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
    <div id="matrices_list" style="visibility: hidden">
		<div class="selector">
			<div class="form-group field-controller_id has-success">
				<label for="matrix" class="control-label"><?= Yii::t('form', 'Матрица') ?></label>
				<div class="help-block"></div>
			</div>    
		</div>
    </div>
    <?= $form->field($model, 'status')->dropDownList($statuses_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
    <?= $form->field($model, 'places_count')->textInput(['style'=>'width:200px;']) ?>
    <?= $form->field($model, 'root', ['inputOptions' => []])->checkbox([
		'inline' => true,
		'enableLabel' => false
	]); 
	?>
    <div class="form-group">
		<?= $form->field($model, 'partner_id')->hiddenInput(['value' => $partner_id])->label(false); ?>
        <?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
