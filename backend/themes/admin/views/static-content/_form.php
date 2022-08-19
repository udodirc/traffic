<?php
use yii\helpers\Html;
//use vova07\imperavi\Widget as Redactor;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\StaticContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="static-content-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
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
		<?php $model->content_no_wysywig = $model->content; ?>
		<?= $form->field($model, 'content_no_wysywig')->textArea(['rows' => '6']) ?>
		<?= $form->field($model, 'content_no_wysywig_on')->hiddenInput(['value'=>0, 'class'=>'content-no-wysywig-on'])->label(false) ?>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
