<?php
namespace common\modules\uploads\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\modules\uploads\models\Files;
use common\modules\uploads\models\Image;

class FilesUploadController extends Controller
{
    public function actionUpload()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$result['files'] = ['name'=>''];
		$get = Yii::$app->request->get();
		$category = (isset($get['category'])) ? $get['category'] : '';
		$params = Yii::$app->params['upload_dir'];
		
		if(isset($params[$category]) && isset($params[$category]['model']) && isset($_FILES[$params[$category]['model']])) 
		{	
			$model = new Files();
			$model->file = \yii\web\UploadedFile::getInstancesByName($params[$category]['model']);
			$alias = isset($params[$category]['alias']) ? $params[$category]['alias'] : 'frontend_uploads';
			$dir = (isset($get['tmp'])) ? \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$params[$category]['tmp'].DIRECTORY_SEPARATOR : \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR;
			$fileType = (isset($get['tmp'])) ? 'tmp' : 'dir';
			
			if(is_dir($dir))
			{	
				foreach($model->file as $file) 
				{
					if($file->saveAs($dir.$file->baseName.'.'.$file->extension))
					{
						$result['files']['name'] = $dir.$file->baseName.'.'.$file->extension;
						$result['files']['imagesize'] = getimagesize($dir.$file->baseName.'.'.$file->extension);
						$result['files']['file_type'] = $fileType;
					}
				}
			}
		}
		
		return $result;
    }
    
    public function actionContentFileUpload()
    {
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$model = new Files();
		
		if(Yii::$app->request->isPost) 
		{	
			$image = new Image;
			$result = $image->singleRawUpload('upload');
			
			// Required: anonymous function reference number as explained above.
			$funcNum = $_GET['CKEditorFuncNum'] ;
				
			// Optional: instance name (might be used to load a specific configuration file or anything else).
			$CKEditor = $_GET['CKEditor'] ;
			
			// Optional: might be used to provide localized messages.
			$langCode = $_GET['langCode'] ;
				 
			// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
			$url = '';
			
			// Usually you will only assign something here if the file could not be uploaded.
			$message = Yii::t('messages', 'Файл не загружен!');
            
            if($result[0])
            {
				$url = \Yii::getAlias('@web').((!strpos(\Yii::getAlias('@web'), 'admin')) ? DIRECTORY_SEPARATOR.'admin' : '').DIRECTORY_SEPARATOR.\Yii::getAlias('@content_uploads').DIRECTORY_SEPARATOR.$result[2];
				$message = Yii::t('messages', 'Файл загружен!');
			}
			
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$funcNum."', '".$url."', '".$message."');</script>";
        }
	}
    
    public function actionDeleteFile()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$post = Yii::$app->request->post();
		$category = (isset($post['category'])) ? $post['category'] : '';
		$file = (isset($post['file'])) ? $post['file'] : '';
		$id = (isset($post['id'])) ? $post['id'] : 0;
		$fileType = (isset($post['file_type'])) ? $post['file_type'] : '';
		$result = false;
		
		if($category != '' && $file != '' && isset(Yii::$app->params['upload_dir'][$category]))
		{	
			$result = Files::deleteFile($category, $file, $id, $fileType);
		}
		
		return $result;
	}
}
