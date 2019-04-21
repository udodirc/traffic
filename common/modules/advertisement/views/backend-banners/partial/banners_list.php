<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
use common\modules\uploads\models\Files;

$files = Files::getFiles($category);

if(isset(Yii::$app->params['upload_dir'][$category])): 
	if(!empty($files)):
	
	foreach($files as $key => $file):
		$file = explode('/', $file);
		$file = end($file);
	?>
	<div class="file_wrap" file_type="file" file="<?= $file?>">
		<div align="right">
			<a class="file_delete" href="#">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</div>
		<div>
			<img src="<?= \Yii::getAlias('@web').DIRECTORY_SEPARATOR.\Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.$file ?>">
		</div>
	</div>
	<?php endforeach; 
	endif;
endif;
?>
