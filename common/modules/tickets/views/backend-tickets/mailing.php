<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('form', 'Сообщение');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(Yii::$app->request->baseUrl.DIRECTORY_SEPARATOR.\Yii::getAlias('@backend_admin_js_dir').DIRECTORY_SEPARATOR.'core.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="reserve-form">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="notice error">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
    <?php $form = ActiveForm::begin([
		'id' => 'filter-form',
		'fieldConfig' => 
		[
			'options' => [
				'tag'=>'span'
            ]
		],
	]);?>
	<div class="form-group" style="overflow:hidden;">
		<?= Html::submitButton(Yii::t('form', 'Очистить поиск'), ['class' => 'button-blue', 'onClick'=>'reset_form();']) ?>
	</div>
	<div class="selector">
		<?= $form->field($model, 'country', [
		'template' => '<div class="col-md-6">
			<div class="controls">
				{label}{input}{hint}{error}
			</div>
		</div>',
		'inputOptions' => []])->dropDownList($country_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
		<?= $form->field($model, 'status', [
		'template' => '<div class="col-md-6">
			<div class="controls">
				{label}{input}{hint}{error}
			</div>
		</div>',
		'inputOptions' => []])->dropDownList($statuses_list, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); 
		?>
	</div>
	<div class="selector">
		<?= $form->field($model, 'partners_offset', [
		'template' => '<div class="col-md-6">
			<div class="controls">
				{label}{input}{hint}{error}
			</div>
		</div>',
		'inputOptions' => []])->textInput(['style'=>'width:200px;']); ?>
		<?= $form->field($model, 'partners_count', [
		'template' => '<div class="col-md-6">
			<div class="controls">
				{label}{input}{hint}{error}
			</div>
		</div>',
		'inputOptions' => []])->textInput(['style'=>'width:200px;']); 
		?>
	</div>
	<div class="selector">
		<?= $form->field($model, 'top_leaders_count', [
		'template' => '<div class="col-md-12">
			<div class="controls">
				{label}{input}{hint}{error}
			</div>
		</div>',
		'inputOptions' => []])->textInput(['style'=>'width:200px;']); ?>
	</div>
	<div class="selector">
		<?= $form->field($model, 'tickets', [
		'template' => '<div class="col-md-6">
			<div class="controls">
				{label}{input}{hint}{error}
			</div>
		</div>',
		'inputOptions' => []])->checkbox([
			'inline' => true,
			'enableLabel' => false
		]); 
		?>
		<?= $form->field($model, 'email', [
		'template' => '<div class="col-md-6">
			<div class="controls">
				{label}{input}{hint}{error}
			</div>
		</div>',
		'inputOptions' => []])->checkbox([
			'inline' => true,
			'enableLabel' => false
		]); 
		?>
	</div>
	<?= $form->field($model, 'subject')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'message')->widget(CKEditor::className(),[
		'editorOptions' => [
			'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
			'inline' => false, //по умолчанию false
			'language' => 'ru',
			'extraPlugins' => 'image',
			'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
		],
	]); ?>
    <div class="form-group">
		<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
