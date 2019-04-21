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
						<?= Html::activeLabel($model, 'text', [
							'class'=>'col-sm-3 control-label',
							'label' => Yii::t('form', 'Отзыв').'<span class="required">*</span>'
						]); ?>
						<div class="col-sm-9">
							<?= $form->field($model, 'feedback')->widget(CKEditor::className(),[
								'editorOptions' => [
									'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
									'inline' => false, //по умолчанию false
									'language' => 'ru',
									'extraPlugins' => 'image',
									'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
								],
							])->label(false); ?>
							<?= Html::error($model, 'text', [
								'class'=>'error'
							]); ?>
                          </div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary']) ?>
						</div>
					</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>	
	</div>
</div>
