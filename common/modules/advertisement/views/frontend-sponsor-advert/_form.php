<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use mihaildev\ckeditor\CKEditor;
use dosamigos\fileupload\FileUpload;
use common\modules\uploads\models\Files;
use yii\helpers\FileHelper;

/* @var $this yii\web\View */
/* @var $model common\modules\advertisement\models\SponsorAdvert */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h6 class="panel-title txt-dark"><?= Html::encode($this->title); ?></h6>
			</div>
            <div class="ibox-content">
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
					<?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
					<?= $form->field($model, 'desc')->widget(CKEditor::className(),[
						'editorOptions' => [
							'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
							'inline' => false, //по умолчанию false
							'language' => 'ru',
							'extraPlugins' => 'image',
							'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
						],
					]); ?>
					<input type="hidden" value="<?=Yii::$app->request->getCsrfToken()?>" />
					<div class="form-group">
						<div id="dropzone">
							<div class="btn_box">
								<div class="btn"><?= Yii::t('form', 'Выберите изображение с компьютера'); ?></div>
							</div>
							<?php 
							if ($url !== ''): 
								$filePath = \Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@sponsor_advert_view').DIRECTORY_SEPARATOR.((isset(Yii::$app->params['upload_dir'][$category]['tmp'])) ? Yii::$app->params['upload_dir'][$category]['tmp'] : '').DIRECTORY_SEPARATOR;
								?>
								<?= FileUpload::widget([
									'model' => $model,
									'attribute' => 'file',
									'url' => ['/uploads/files-upload/upload?category='.$category.'&tmp=1'], // your url, this is just for demo purposes,
									'options' => ['accept' => 'image/*'],
									'clientOptions' => [
										'maxFileSize' => 2000000
									],
									// Also, you can specify jQuery-File-Upload events
									// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
									'clientEvents' => [
										'fileuploaddone' => 'function(e, data) 
										{
											var name = data.files[0].name;
											var item_id = ($("div.file_wrap").length > 0) ? $("div.file_wrap").length : 0;
											item_id += 1;
											
											$("#files-upload").append("<div class=\"file_wrap\" file=\"" + name + "\" '.(($thumbnail['width'] > 0 && $thumbnail['height'] > 0) ? 'style=\"width:'.$thumbnail['width'].'px; height:'.($thumbnail['height'] + 20).'px;\"' : '').' category=\"'.$category.'\" file_type=\"tmp\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div><img src=\"" + "'.\Yii::getAlias('@web').((!strpos(\Yii::getAlias('@web'), 'admin')) ? DIRECTORY_SEPARATOR.'admin' : '').DIRECTORY_SEPARATOR.\Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR.'" + name + "\" '.(($thumbnail['width'] > 0) ? 'width=\"'.$thumbnail['width'].'px;\"' : '').' '.(($thumbnail['height'] > 0) ? 'height=\"'.$thumbnail['height'].'px;\"' : '').'></div></div>");
										}',
										'fileuploadfail' => 'function(e, data)
										{
											console.log(e);
											console.log(data);
										}',
									],
								]);?>
							<?php endif; ?>	
						</div>
						<div id="files-upload" class="files_<?= $category; ?>" url="<?= Url::base(); ?>" wrap_id="1" item_id="<?= $id; ?>">
							<?=$this->render('partial/banner_view', [
								'category' => $category,
								'id' => $id,
								'thumbnail' => $thumbnail
							]);?>
						</div>
					</div>
					<div class="form-group">
						<?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => $model->isNewRecord ? 'button-blue' : 'button-blue']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>	
	</div>
</div>
