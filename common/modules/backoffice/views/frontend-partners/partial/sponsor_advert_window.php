<?php
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
use common\modules\uploads\models\Files;
	
	\yii\bootstrap\Modal::begin([
		'headerOptions' => ['id' => 'modal-message-header'],
		'id' => 'modal-message',
		'size' => 'modal-lg',
		//keeps from closing modal with esc key or by clicking out of the modal.
		// user must click cancel or X to close
		'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
	]);
?>
	<div id='modal-message-text'>
		<div class="sponsor-advert-wrap name">
			<h1><?= ($sponsorAdvert !== null) ? $sponsorAdvert->name : ''; ?></h1>
		</div>
		<div class="sponsor-advert-wrap banner">
			<?php
			$category = 'sponsor_advert';
			$files = Files::getFiles($category, false, $sponsorAdvert->id);
			
			if(!empty($files)):
				$thumbnail = (isset(Yii::$app->params['upload_dir'][$category])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
				$filename = end($files);
				$file = explode('/', $filename);
				$file = end($file);
				$url = Files::getUploadDirUrl($sponsorAdvert->id, $category);
			
				if($url !== ''):
					
					$filePath = \Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@sponsor_advert_view').DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR.$file;
					
					if(file_exists($filename) && (is_readable($filename))): 
					
						echo Html::a(Html::img($filePath, ['width'=>$thumbnail['width'], 'height'=>$thumbnail['height'], 'alt'=>'']), $sponsorAdvert->link, ['target'=>'blank']);
					
					endif;
		
				endif;
				
			else:
					
				echo Html::a(Yii::t('form', 'Узнать подробнее'), $sponsorAdvert->link, ['target'=>'blank']);
	
			endif;
			?>
		</div>
		<div class="sponsor-advert-wrap desc">
			<?= ($sponsorAdvert !== null) ? $sponsorAdvert->desc : ''; ?>
		</div>
		<div class="sponsor-advert-wrap desc">
			<?= Html::a(Yii::t('form', 'Войти в профиль'), ['/partners/profile/'.$id], []); ?>
		</div>
	</div>
<?php \yii\bootstrap\Modal::end(); ?>
