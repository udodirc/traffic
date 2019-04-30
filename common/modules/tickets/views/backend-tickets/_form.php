<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\modules\news\models\News */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="news-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'login')->textInput() ?>
    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
		'editorOptions' => [
			'class' => 'form-control',
			'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
			'inline' => false, //по умолчанию false
			'language' => 'ru',
			'extraPlugins' => 'image',
			'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
		],
	])->label(false); ?>
    <?/*= $form->field($model, 'text')->textArea(['rows' => '6'])*/ ?>
    <div class="form-group">
	<?= $form->field($messageForm, 'reCaptcha')->widget(
		common\widgets\captcha\ReCaptcha::className(),
		['siteKey' => '6LeiwJ8UAAAAADcw3ymj25xEht39C_nVMloTA84f']
	); ?>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Редактировать'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
