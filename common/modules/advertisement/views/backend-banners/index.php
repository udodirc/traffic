<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */

$this->title = Yii::t('form', 'Баннеры');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="structure-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
</div>
<div id="dropzone">
	<div class="btn_box">
		<div class="btn">Выберите с компьютера</div>
	</div>
	<?php if ($url !== ''): ?>
		<?= FileUpload::widget([
			'model' => $model,
			'attribute' => 'file',
			'url' => ['/uploads/files-upload/upload?category='.$category], // your url, this is just for demo purposes,
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
						$("<div class=\"file_wrap\" file=\"" + name + "\" '.(($thumbnail['width'] > 0 && $thumbnail['height'] > 0) ? 'style=\"width:'.$thumbnail['width'].'px; height:'.($thumbnail['height'] + 20).'px;\"' : '').' category=\"'.$category.'\" file_type=\"file\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div><img src=\"" + "'.\Yii::getAlias('@uploads').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.'" + name + "\" '.(($thumbnail['width'] > 0) ? 'width=\"'.$thumbnail['width'].'px;\"' : '').' '.(($thumbnail['height'] > 0) ? 'height=\"'.$thumbnail['heighth'].'px;\"' : '').'></div></div>").insertAfter($(".file_wrap:last"));
					}
					else
					{
						
						$("#files-upload").append("<div class=\"file_wrap\" file=\"" + name + "\" '.(($thumbnail['width'] > 0 && $thumbnail['height'] > 0) ? 'style=\"width:'.$thumbnail['width'].'px; height:'.($thumbnail['height'] + 20).'px;\"' : '').' category=\"'.$category.'\" file_type=\"file\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div><img src=\"" + "'.\Yii::getAlias('@uploads').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.'" + name + "\" '.(($thumbnail['width'] > 0) ? 'width=\"'.$thumbnail['width'].'px;\"' : '').' '.(($thumbnail['height'] > 0) ? 'height=\"'.$thumbnail['heighth'].'px;\"' : '').'></div></div>");
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
	<?=$this->render('partial/banners_list', [
		'category' => $category
	]);?>
<!--	--><?php //= \yii\base\View::render(
//		'//../../common/modules/uploads/views/file-list/list-file',
//		[
//			'category'=>$category,
//			'id'=>0,
//			'thumbnail'=>$thumbnail,
//			'tmp'=>false,
//		]
//	);
//	?>
	</div>
</div>
