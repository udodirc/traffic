<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\components\DropDownListHelper;
//use vova07\imperavi\Widget as Redactor;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="content-form">
    <?php $form = ActiveForm::begin();?>
    <div class="selector">
	<?= DropDownListHelper::getControlersDropDownList($form, $model, 'controller_id', 'content_controllers', 'name', [
		'prompt'=>Yii::t('form', 'Выберите контроллер'),
		'style'=>'width:300px;'
	]); ?>
    </div>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 100]) ?>
    <div class="form-wysywig">
		<?= $form->field($model, 'content')->widget(CKEditor::className(),[
			'editorOptions' => [
				'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
				'inline' => false, //по умолчанию false
				'language' => 'ru',
				'extraPlugins' => 'image',
				'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
			],
		]); ?>
	</div>
	<?= Html::a(Yii::t('form', 'Без wysywig'), '#', ['class' => 'no-wysywig']) ?>
	<div class="form-no-wysywig">
		<?= $form->field($model, 'content_no_wysywig')->textArea(['rows' => '6']) ?>
		<?= $form->field($model, 'content_no_wysywig_on')->hiddenInput(['value'=>0, 'class'=>'content-no-wysywig-on'])->label(false) ?>
	</div>
	<?= $form->field($model, 'style')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 100]) ?>
    <?= $form->field($model, 'meta_description')->textArea(['rows' => '6']) ?>
    <?= $form->field($model, 'meta_keywords')->textArea(['rows' => '6']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Редактировать'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
