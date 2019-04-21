<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model common\modules\slider\models\Slider */
/* @var $form yii\widgets\ActiveForm */

$dir = (isset(Yii::$app->params['upload_dir'][$category]['tmp']) && Yii::$app->params['upload_dir'][$category]['tmp'] != '') 
? Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR 
: ((Yii::$app->params['upload_dir'][$category]['dir'] != '') ? Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR : '');
$dir = \Yii::getAlias('@web').DIRECTORY_SEPARATOR.\Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.$dir;
?>
<div class="slider-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>
    <div id="dropzone">
		<div class="btn_box">
			<div class="btn">Выберите с компьютера</div>
		</div>
		<?php if ($url !== ''): ?>
			<?= FileUpload::widget([
				'model' => $model,
				'attribute' => 'file',
				'url' => ['/uploads/files-upload/upload?category='.$category.'&tmp=tmp'], // your url, this is just for demo purposes,
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
						
						if($(".file_wrap").length > 0)
						{	
							$("<div class=\"file_wrap\" file=\"" + name + "\" '.(($thumbnail['width'] > 0 && $thumbnail['height'] > 0) ? 'style=\"width:'.$thumbnail['width'].'px; height:'.($thumbnail['height'] + 20).'px;\"' : '').' category=\"'.$category.'\" file_type=\"tmp\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div><img src=\"" + "'.$dir.'" + name + "\" '.(($thumbnail['width'] > 0) ? 'width=\"'.$thumbnail['width'].'px;\"' : '').' '.(($thumbnail['height'] > 0) ? 'height=\"'.$thumbnail['height'].'px;\"' : '').'></div></div>").insertAfter($(".file_wrap:last"));
						}
						else
						{
							
							$("#files-upload").append("<div class=\"file_wrap\" file=\"" + name + "\" '.(($thumbnail['width'] > 0 && $thumbnail['height'] > 0) ? 'style=\"width:'.$thumbnail['width'].'px; height:'.($thumbnail['height'] + 20).'px;\"' : '').' category=\"'.$category.'\" file_type=\"tmp\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div><img src=\"" + "'.$dir.'" + name + "\" '.(($thumbnail['width'] > 0) ? 'width=\"'.$thumbnail['width'].'px;\"' : '').' '.(($thumbnail['height'] > 0) ? 'height=\"'.$thumbnail['height'].'px;\"' : '').'></div></div>");
						}
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
		<div id="files-upload" class="files_<?= $category?>" url="<?= Url::base(); ?>" wrap_id="1">
		<?= \yii\base\View::render(
			'//../../common/modules/uploads/views/file-list/one-file',
			[
				'category'=>$category,
				'id'=>$id,
				'thumbnail'=>$thumbnail,
				'tmp'=>true,
				'file_type'=>($id > 0) ? 'dir' : 'file',
			]
		);
		?>
		</div>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Редактировать'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
