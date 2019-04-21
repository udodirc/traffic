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
	<div class="banner_wrap">
		<div class="file_wrap" file_type="tmp" file="<?= $file?>">
			<div>
				<img src="<?= \Yii::getAlias('@uploads2').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.$file ?>">
			</div>
		</div>
		<div class="banner_text">
			<?= Html::textarea('banner_code', '<a href="'.Url::base(true).'/ref/'.((!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->login : '').'" target="_blank"><img src="'.Url::base(true).DIRECTORY_SEPARATOR.\Yii::getAlias('@admin_uploads').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.$file.'"></a>', ['class'=>'banner_code', 'id' => 'copy_banner_'.$key]); ?>
			<?= Html::button(Yii::t('form', 'Копировать'), ['class' => 'btn btn-success banner', 'onclick'=>"copyToClipboard('#copy_banner_".$key."')"]) ?>
		</div>
	</div>
	<?php endforeach; 
	endif;
endif;
?>
