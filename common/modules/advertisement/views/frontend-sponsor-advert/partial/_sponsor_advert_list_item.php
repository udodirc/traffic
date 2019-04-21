<?php
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
use common\modules\uploads\models\Files;
?>
<div class="col-lg-12">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="pull-left bold">
				<h6 class="panel-title txt-light"><?= Html::a($model->name, $model->link, ['target'=>'blank']); ?></h6>
			</div>
			<div class="pull-right news">
				<?= date("Y-m-d", $model->created_at); ?>
			</div>
			<div class="clearfix"></div>        
		</div>
        <div class="panel-body">
			<?= $model->desc; ?>
			<div class="sponsor-advert-wrap banner">
				<?php
				$category = 'sponsor_advert';
				$files = Files::getFiles($category, false, $model->id);
				
				if(!empty($files)):
					$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
					$filename = end($files);
					$file = explode('/', $filename);
					$file = end($file);
					$url = Files::getUploadDirUrl($model->id, $category);
				
					if($url !== ''):
						
						$filePath = \Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@sponsor_advert_view').DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR.$file;
						
						if(file_exists($filename) && (is_readable($filename))): 
						
							echo Html::a(Html::img($filePath, ['width'=>$thumbnail['width'], 'height'=>$thumbnail['height'], 'alt'=>'']), $model->link, ['target'=>'blank']);
						
						endif;
			
					endif;
					
				else:
						
					echo Html::a(Yii::t('form', 'Узнать подробнее'), $model->link, ['target'=>'blank']);
		
				endif;
				?>
			</div>
		</div>
	</div>
</div>
