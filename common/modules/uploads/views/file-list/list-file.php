<?php
use yii\helpers\Url;
use yii\helpers\FileHelper;
use common\modules\uploads\models\Files;

$files = Files::getFiles($category, $tmp);
$wrap_id = (isset($wrap_id)) ? $wrap_id : 1;
$alias = isset(Yii::$app->params['upload_dir'][$category]['alias']) ? Yii::$app->params['upload_dir'][$category]['alias'] : 'frontend_uploads';

if(isset(Yii::$app->params['upload_dir'][$category])): 
	if(!empty($files)):
	$dir = ($tmp) ? Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR : Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR;
	
	foreach($files as $key => $file):
		$file = explode('/', $file);
		$file = end($file);
		
		if($thumbnail['width'] <= 0 && $thumbnail['height'] <= 0)
		{
			if(is_file(Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$file))
			{
				list($thumbnail['width'], $thumbnail['height'], $type, $attr) = getimagesize(Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$file);
			}
		}
	?>
	<div class="file_wrap" file_type="<?= ($tmp) ? 'tmp' : 'dir'; ?>" file="<?= $file?>" <?php if($thumbnail['width'] > 0 && $thumbnail['height'] > 0): ?>style="width:<?= $thumbnail['width'] ?>px; height:<?= $thumbnail['height'] ?>px; overflow:hidden; margin:10px;"<?php else: ?>style="width:auto; height:auto; margin:10px;"<?php endif; ?>>
		<div align="right">
			<a class="file_delete" href="#">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</div>
		<div class="image-wrap">
			<img src="<?= Yii::getAlias('@root_dir').DIRECTORY_SEPARATOR.$dir.$file ?>"<?php if($thumbnail['width'] > 0 && $thumbnail['height'] > 0): ?> width="<?= $thumbnail['width'] ?>px;" height="<?= $thumbnail['height'] ?>px;"<?php endif; ?>>
		</div>
	</div>
	<?php endforeach; 
	endif;
endif;
?>
<div class="clear"></div>
<?php
if($id > 0):
	$url = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail']['dir'])) ? Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail']['dir'] : Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id;
	if(is_dir(Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$url)):
	$files = FileHelper::findFiles(Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$url); ?>
	<div id="files-upload" class="files_<?= $category?>" url="<?= Url::base(); ?>" wrap_id="<?= $wrap_id; ?>" item_id="<?= $id; ?>"> 		
		<?php if(!empty($files)):
		foreach($files as $key => $file):
			$file = explode('/', $file);
			$file = end($file);
			?>
			<div class="file_wrap" file_type="file" file="<?= $file?>" style="width:<?= $thumbnail['width'] ?>px; height:<?= $thumbnail['height'] ?>px; overflow:hidden; margin:10px;">
				<div align="right">
					<a class="file_delete" href="#">
						<span class="glyphicon glyphicon-trash"></span>
					</a>
				</div>
				<div>
					<img src="<?= Yii::getAlias('@root_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR.$file?>" width="<?= $thumbnail['width'] ?>px;" height="<?= $thumbnail['height'] ?>px;">
				</div>
			</div>
		<?php endforeach; 
		endif;?>
	</div>
	<?php endif; 
endif; ?>
