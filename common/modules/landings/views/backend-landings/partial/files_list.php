<?php
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
use common\components\FileHelper;

$i=1;
?>
<div class="file_list">
<?php
foreach($files as $dir=>$dirData)
{
	if(is_dir($dir)) 
	{
		$dirName = explode('/', $dir);
		$dirName = end($dirName);
	?>
	<div class="dir_list">
		<div id="files-upload" class="files_<?= $category?>" url="<?= Url::base(); ?>" wrap_id="<?= $i?>">
			<div class="file_wrap" file_type="dir_relative_path" file="<?= $dir?>">
				<span class="glyphicon glyphicon glyphicon-folder-close"></span>
				<?= $dirName; ?>
				<a class="file_delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
				<?php
					foreach($dirData as $i=>$file)
					{
						if(is_file($file)) 
						{
							$fileName = explode('/', $file);
							$fileName = end($fileName);
							$file = substr($file, -(strlen($file) - strlen($filesDir)));
						?>
							<div class="file_wrap" file_type="file" file="<?= $file?>">
								<span class="glyphicon glyphicon glyphicon-file"></span>
								<a class="file_link" href="edit-file?id=<?= $model->id?>&file=<?= $file?>"><?= $fileName; ?></a>
								<a class="file_delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
							</div>
						<?php
						}
					}
				?>
			</div>
		</div>
	<?php
		$i++;
	}
	?>
	</div>
	<?php
}
?>
</div>
