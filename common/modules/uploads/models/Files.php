<?php
namespace common\modules\uploads\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Url;
use common\components\FileHelper;
use common\modules\uploads\models\Image;

/**
* Files is the model to work with file uploading
*/
class Files extends Model
{
	/**
	* @var UploadedFile|Null file attribute
	*/
	public $file;

	/**
	* @return array the validation rules.
	*/
	public function rules()
	{
		return [
			[['file'], 'file'],
		];
	}
	
	public static function deleteFile($category, $file, $id, $fileType)
    {
		$result = false;
		$params = Yii::$app->params['upload_dir'][$category];
		$alias = isset($params['alias']) ? $params['alias'] : 'frontend_uploads';
		
		switch($fileType)
		{
			case 'dir':
			$path = $params['uploads'].DIRECTORY_SEPARATOR.$id;
			break;
			
			case 'file':
			$path = ($id > 0) ? $id.DIRECTORY_SEPARATOR.$file : $file;
			break;
			
			case 'tmp':
			$path = $params['tmp'].DIRECTORY_SEPARATOR.$file;
			break;
		}
		
		if($fileType == 'dir')
		{
			$dir = \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id;
			
			if(is_dir($dir))
			{	
				FileHelper::removeDirectory($dir);
				$result = true;
			}
		}
		elseif($fileType == 'dir_relative_path')
		{
			if(is_dir($file))
			{	
				FileHelper::removeDirectory($file);
				
				if(!is_dir($file))
				{
					$result = true;
				}
			}
		}
		else
		{	
			if(file_exists(\Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$path))
			{
				if(unlink(\Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$path))
				{
					$result = true;
				}
			}
		
			if(isset($params['image_sizes']))
			{
				$path = $params['uploads'].DIRECTORY_SEPARATOR.$id;
			
				foreach($params['image_sizes'] as $type => $item)
				{	
					if(isset($item['dir']))
					{
						$fullPath = $path.DIRECTORY_SEPARATOR.$item['dir'].DIRECTORY_SEPARATOR.$file;
						
						if(file_exists($fullPath))
						{	
							$result = (unlink($fullPath)) ? true : false;
						}
					}
				}
			}
			elseif(isset($params['uploads']))
			{
				$path = $params['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$file;
			
				if(file_exists($path))
				{
					if(unlink($path))
					{
						$result = true;
					}
				}	
			}
		}
		
		return $result;
	}
	
	public static function getFiles($category, $tmp = false, $id = 0)
    {
		$result = [];
		
		if(isset(Yii::$app->params['upload_dir'][$category]))
		{
			$params = Yii::$app->params['upload_dir'][$category];
			$alias = isset($params['alias']) ? $params['alias'] : 'frontend_uploads';
			
			if(\Yii::getAlias('@'.$alias) != '')
			{			
				if($tmp)
				{
					$dir = \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$params['tmp'];
				}
				else
				{
					$dir = ($id > 0) ? \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$params['uploads'].DIRECTORY_SEPARATOR.$id : \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR;
				}
				
				if(is_dir($dir) && $dir != '/' && \Yii::getAlias('@'.$alias) != '')
				{	
					$result = FileHelper::findFiles($dir);
				}
			}
		}
		
		return $result;
	}
	
	public static function uploadImageFromTmpDir($category, $objectID, $multi = false, $separateFolder = false)
	{
		$result = false;
		$params = Yii::$app->params['upload_dir'];
		
		if($category != '' && $objectID > 0 && isset($params[$category]))
		{	
			$tmpDir = \Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.$category.DIRECTORY_SEPARATOR.$params[$category]['tmp'];
			$uploadDir = \Yii::getAlias('@backend_upload_dir').DIRECTORY_SEPARATOR.$category.DIRECTORY_SEPARATOR.$params[$category]['uploads'];
			$files = FileHelper::findFiles($tmpDir);
			
			if(!empty($files))
			{	
				if(is_dir($uploadDir))
				{
					if($separateFolder)
					{
						$uploadDir.= DIRECTORY_SEPARATOR.$objectID;
						
						if(!is_dir($uploadDir))
						{
							if(!FileHelper::createDirectory($uploadDir))
							{	
								return false;
							}
						}
						else
						{
							if(!$multi)
							{
								if(!FileHelper::removeAllFilesInDir($uploadDir))
								{	
									return false;
								}
							}
						}
					}
					
					if($multi)
					{	
						$result = self::multiUploadFromTmpDir($params, $category, $uploadDir, $tmpDir, $files);
					}
					else
					{
						$result = self::singleUploadFromTmpDir($params, $category, $tmpDir, $uploadDir, $files);
					}
					
					if($result)
					{
						$result = FileHelper::removeAllFilesInDir($tmpDir);
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function singleUploadFromTmpDir($params, $category, $tmpDir, $uploadDir, $files)
	{
		$result = false;
		$image = new Image;
		$file = array_shift($files);
		$file = explode('/', $file);
		$file = end($file); 
		
		if(isset($params[$category]['origin_image']['width']) && isset($params[$category]['origin_image']['width']))
		{
			$maxWidth = $params[$category]['origin_image']['width'];
			$maxHeight = $params[$category]['origin_image']['height'];
		}
		else
		{
			$maxWidth = 2000;
			$maxHeight = 600;
		}
				
		$result = $image->makeImageFromTmpDir($file, $tmpDir, $uploadDir, $maxWidth, $maxHeight, false, false, true);
		
		return $result;
	}
	
	public static function multiUploadFromTmpDir($params, $category, $uploadDir, $tmpDir, $files)
	{
		$result = false;
		
		if(isset($params[$category]['image_sizes']))
		{
			$image = new Image;
			
			$sizes = $params[$category]['image_sizes'];
							
			foreach($sizes as $type => $size)
			{
				$maxWidth = $size['width'];
				$maxHeight = $size['height'];
				$dir = $uploadDir.DIRECTORY_SEPARATOR.$size['dir'];
								
				if(!is_dir($dir))
				{
					if(!FileHelper::createDirectory($dir))
					{
						return false;
					}
				}
								
				foreach($files as $key=>$file)
				{
					$file = explode('/', $file);
					$file = end($file);
					$crop = (isset($size['crop'])) ? $size['crop'] : false;
					$resize = (isset($size['resize'])) ? $size['resize'] : false;
									
					$result = $image->makeImageFromTmpDir($file, $tmpDir, $dir, $maxWidth, $maxHeight, $resize, $crop);
				}
			}
		}
		
		return $result;
	}
	
	public static function createTextFile($file, $text, $mode = "w+")
    {
		$fp = fopen($file, $mode);
		
		if($fp)
		{	
			if(fwrite($fp, $text))
			{	
				if(fclose($fp))
				{	
					return true;
				}
			}
		}
		
		return false;
	}
	
	public static function readTextFile($file)
    {
		$result = file_get_contents($file);
		
		if(!$result)
		{
			return '';
		}
		
		return $result;
	}
	
	public static function getFileFromFileList($files)
    {
		$result = '';
		
		if(is_array($files) && !empty($files))
		{
			$file = end($files);
			$file = explode('/', $file);
			
			if(is_array($file) && !empty($file))
			{
				$result = end($file);
			}
		}
		
		return $result;
	}
	
	public static function getUploadDirUrl($id, $category)
    {
		$result = '';
		
		if($id > 0)
		{
			if(isset(Yii::$app->params['upload_dir'][$category]['uploads']))
			{
				if(isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail']['dir']))
				{
					$result = Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail']['dir'];
				}
			}
		}
		else
		{
			if(isset(Yii::$app->params['upload_dir'][$category]['tmp']))
			{
				$result = Yii::$app->params['upload_dir'][$category]['tmp'];
			}
		}
		
		return $result;
	}
}
?>
