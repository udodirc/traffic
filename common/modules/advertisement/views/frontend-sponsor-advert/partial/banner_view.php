<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
use common\modules\uploads\models\Files;

$this->registerJsFile(Yii::$app->request->baseUrl.'/common/modules/uploads/assets/js/uploads.js',['depends' => [\yii\web\JqueryAsset::className()]]);

if(isset(Yii::$app->params['upload_dir'][$category]))
{ 
	$tmp = ($id > 0) ? false : true;
	$files = Files::getFiles($category, $tmp, $id);
	
	if(!empty($files) && !($tmp))
	{
		$fileType = 'dir';
		$filename = end($files);
		$file = explode('/', $filename);
		$file = end($file);
		$url = Files::getUploadDirUrl($id, $category);
		$filePath = ($url !== '') ? \Yii::getAlias('@web').((!strpos(\Yii::getAlias('@web'), 'admin')) ? DIRECTORY_SEPARATOR.'admin' : '').DIRECTORY_SEPARATOR.\Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail']['dir'].DIRECTORY_SEPARATOR.$file : '';
	}
	else
	{
		$fileType = 'tmp';
		$files = Files::getFiles($category, true);
		$filename = end($files);
		$file = explode('/', $filename);
		$file = end($file);
		$filePath = \Yii::getAlias('@web').((!strpos(\Yii::getAlias('@web'), 'admin')) ? DIRECTORY_SEPARATOR.'admin' : '').DIRECTORY_SEPARATOR.\Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR.$file;
	}
	
	if(file_exists($filename) && is_readable($filename) && $filePath != ''): ?>	
		<div class="file_wrap" file_type="<?= $fileType; ?>" file="<?= $file; ?>">
			<div align="right">
				<a class="file_delete" href="#">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</div>
			<div>
				<?= Html::img($filePath, ['width'=>$thumbnail['width'], 'height'=>$thumbnail['height'], 'alt'=>'']); ?>
			</div>
		</div>
	<?php 
	endif;
}
?>
