<?php
use yii\helpers\Html;
use yii\helpers\Url;
use vova07\imperavi\Widget as Redactor;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\AdminMenu */

$this->title = Yii::t('form', 'Редактирование: ', [
    'modelClass' => 'Content',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Контент'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="content-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="menu-form">
	<?php $form = ActiveForm::begin(); ?>
		<div class="form-group">
			<?= ($model->menu_name !== '' && $model->menu_name !== NULL) ? $model->menu_name : Yii::t('form', 'Меню нет'); ?>
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
<!--		--><?php //= $form->field($model, 'content')->widget(Redactor::className(), [ 'settings' => [
//				'lang'        => 'ru',
//				'minHeight'   => 200,
//				'imageManagerJson' => Url::to(['/content/images-get']),
//				'imageUpload' => Url::to(['/content/uploads']),
//				'plugins'     => ['fullscreen', 'table', 'video', 'imagemanager']
//			]
//		]); ?>
		<?= Html::a(Yii::t('form', 'Без wysywig'), '#', ['class' => 'no-wysywig']) ?>
		<div class="form-no-wysywig">
			<?php $model->content_no_wysywig = $model->content; ?>
			<?= $form->field($model, 'content_no_wysywig')->textArea(['rows' => '6']) ?>
			<?= $form->field($model, 'content_no_wysywig_on')->hiddenInput(['value'=>0, 'class'=>'content-no-wysywig-on'])->label(false) ?>
		</div>
		<?= $form->field($model, 'style')->textInput(['maxlength' => 100]) ?>
		<?= $form->field($model, 'meta_title')->textInput(['maxlength' => 100]) ?>
		<?= $form->field($model, 'meta_description')->textArea(['rows' => '6']) ?>
		<?= $form->field($model, 'meta_keywords')->textArea(['rows' => '6']) ?>
		<?= $form->field($model, 'id')->hiddenInput(['value'=>$id])->label(false) ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
		</div>
	<?php ActiveForm::end(); ?>
	</div>
</div>
