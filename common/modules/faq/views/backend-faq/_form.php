<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//use vova07\imperavi\Widget as Redactor;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="faq-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php if(!$update):?>
    <div class="selector">
    <?= $form->field($model, 'type')->dropDownList($typesList, 
	[
		'prompt'=>Yii::t('form', 'Выберите тип'),
		'style'=> 'width:300px;',
		'class'=>'form-control',
		'id' => 'training_id'
    ]);
    ?>
    </div>
    <?php endif; ?>
    <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'answer')->widget(CKEditor::className(),[
		'editorOptions' => [
			'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
			'inline' => false, //по умолчанию false
			'language' => 'ru',
			'extraPlugins' => 'image',
			'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
		],
	]); ?>
    <?/*= $form->field($model, 'answer')->widget(Redactor::className(), [ 'settings' => [ 
		'lang'        => 'ru',
		'minHeight'   => 200,
		'imageManagerJson' => Url::to(['/content/images-get']),
		'imageUpload' => Url::to(['/content/uploads']),
		'plugins'     => ['fullscreen', 'table', 'video', 'imagemanager']
		]
    ]); */?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Редактировать'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
