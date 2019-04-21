<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\modules\tickets\models\Tickets */
/* @var $form yii\widgets\ActiveForm */
$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Yii::$app->request->baseUrl.'vendors/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h6 class="panel-title txt-dark"><?= Html::encode($this->title); ?></h6>
			</div>
            <div class="ibox-content">
				<div class="form-group">
					<?php $form = ActiveForm::begin([
						'options' => ['class'=>'form-horizontal'],
					]); 
					?>
					<div class="form-group">
						<?= Html::activeLabel($model, 'subject', [
							'class'=>'col-sm-2 control-label',
							'label' => Yii::t('form', 'Тема').'*'
						]); ?>
						<div class="col-sm-10">
							<div class="input-group m-b">
								<?= Html::activeTextInput($model, 'subject', ['class'=>'form-control', 'placeholder'=>Yii::t('form', 'Тема')]); ?>
							</div>
							<div class="help-block with-errors">
								<?= Html::error($model, 'subject', []); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<?= Html::activeLabel($model, 'text', [
							'class'=>'col-sm-2 control-label',
							'label' => Yii::t('form', 'Текст сообщения').'*'
						]); ?>
						<div class="col-sm-10">
							<div class="input-group m-b">
								<?= $form->field($model, 'text')->widget(CKEditor::className(),[
									'editorOptions' => [
										'class' => 'form-control',
										'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
										'inline' => false, //по умолчанию false
										'language' => 'ru',
										'extraPlugins' => 'image',
										'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
									],
								]); 
								?>
							</div>
							<div class="help-block with-errors">
								<?= Html::error($model, 'text', []); ?>
							</div>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-2">
							<?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary']) ?>
						</div>
					</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>	
	</div>
</div>
