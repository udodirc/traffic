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
?>
<div class="mailing-form">
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
	<?php $form = ActiveForm::begin();?>
    <?=$form->field($model, 'tickets')->checkbox([
		'inline' => true,
		'enableLabel' => false
	]);?>
	<?=$form->field($model, 'email')->checkbox([
		'inline' => true,
		'enableLabel' => false
	]);?>
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
