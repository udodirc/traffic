<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\modules\feedback\models\Feedback */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feedback-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'feedback')->widget(CKEditor::className(),[
		'editorOptions' => [
			'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
			'inline' => false, //по умолчанию false
			'language' => 'ru',
			'extraPlugins' => 'image',
			'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
		],
	]); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
