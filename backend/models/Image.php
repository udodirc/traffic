<?php
namespace app\models;

use Yii;
/**
 * Image class.
 * Image class is the class for working
 * with image data.
 */
class Image 
{	
	/**
	* Calculate size of image.
	* @param $width passes width of image.
	* @param $big define big or small size of image to calculate.
	* @return array.
	*/
	public static function calculateImageSize($width, $height, $maxWidth, $maxHeight)
	{
		$size = array();
		
		if($maxWidth > 0)
		{
			$proportion = ($width > $height) ? round($width / $height, 2) : round($height / $width, 2);
			
			if($maxWidth == $maxHeight)
			{
				if($width > $height)
				{	
					$size[0] = round($maxHeight * $proportion, 0);
					$size[1] = $maxHeight;
				}
				else
				{
					$size[0] = $maxWidth;
					$size[1] = round($maxWidth * $proportion, 0);
				}
			}
			else
			{
				if($width > $height)
				{	
					$size[0] = $maxWidth;
					$size[1] = round($maxWidth / $proportion, 0);
				}
				else
				{
					$size[0] = round($maxHeight / $proportion, 0);
					$size[1] = $maxHeight;
				}
			}
		}
		else
		{
			$size[0] = $width;
			$size[1] = $height;
		}
		
		return $size;
	}
	
	/**
	* Make image in server side from temporary directory.
	* @param $sourceFile passes filename to upload.
	* @param $tmpDir passes path of temporary directory.
	* @param $uploadDir  passes path of uploaded directory.
	* @param $fileID passes id of filename to upload.
	* @param $maxWidth passes maximal width of image for calculating of image size.
	* @param $quality passes quality of image in jpeg format.
	* method is static
	* @return bool.
	*/
	public function makeImageFromTmpDir($sourceFile, $tmpDir, $uploadDir, $maxWidth, $maxHeight, $resize = true, $crop = false, $copy = false, $quality = 100)
	{	
		$result = false;
		
		//Extension of image source.
		$ext = substr($sourceFile, 1 + strrpos($sourceFile, "."));
		
		//Source file path
		$filetmp = $tmpDir.DIRECTORY_SEPARATOR.$sourceFile;
		
		//Get of image size
		$imageSize = getimagesize($filetmp);
		
		//Set image size
		if($maxWidth == 0)
		{
			$maxWidth = $imageSize[0];
		}
		
		//Set image size
		if($maxHeight == 0)
		{
			$maxHeight = $imageSize[1];
		}
		
		if((($maxWidth >= $imageSize[0]) && ($maxHeight >= $imageSize[1])) || ($copy))
		{	
			//echo $uploadDir.DIRECTORY_SEPARATOR.$sourceFile.'Work!'.'<br/>';
			if(copy($filetmp, $uploadDir.DIRECTORY_SEPARATOR.$sourceFile))
			{	
				$result = true;
			}
		}
		else
		{	
			if($resize)
			{
				//Create and resize image in server side from temp directory
				$result =  $this->resizeImage($filetmp, $uploadDir.DIRECTORY_SEPARATOR.$sourceFile, $imageSize, $maxWidth, $maxHeight, $ext, $quality);
			}
			
			if($crop)
			{	
				//Crop image
				$result = $this->cropImage($uploadDir.DIRECTORY_SEPARATOR.$sourceFile, $maxWidth, $maxHeight, $ext, $quality);
			}
		}
		
		return $result;
	}
	
	public function cropImage($uploadfile, $maxWidth, $maxHeight, $ext, $quality)
	{
		$result = false;
		
		//Creating of dummy image
		$sourceFile = $this->createDummyImage($uploadfile, $ext);
		
		//If dummy jpg image is created	
		if($sourceFile)
		{
			//Get of image size
			$imageSize = getimagesize($uploadfile);

			//Set crop coordinates
			$x = ($imageSize[0] > $imageSize[1]) ? ($imageSize[0] - $maxWidth) / 2 : 0;
			$y = ($imageSize[0] > $imageSize[1]) ? 0 : ($imageSize[1] - $maxHeight) / 2;

			$cropArray = array('x' => $x , 'y' => $y, 'width' => $maxWidth, 'height'=> $maxHeight);
			$cropFile = imagecrop($sourceFile, $cropArray);
			
			//If image is croped	
			if($cropFile)
			{
				$result = $this->createRealImage($cropFile, $ext, $uploadfile, $quality);
			}
		}
		
		return $result;
	}
	
	/**
	* Create and resize image in server side from temp directory.
	* @param $filetmp passes image data.
	* @param $uploadfile passes pathname of uploaded file.
	* @param $maxWidth passes maximal width of image for calculating of image size.
	* @param $ext passes extension of image source.
	* @param $quality passes quality of image in jpeg format.
	* @return bool.
	*/
	public function resizeImage($filetmp, $uploadfile, $imageSize, $maxWidth, $maxHeight, $ext, $quality)
	{
		$result = false;
		
		//Calculate of image size	
		$size = self::calculateImageSize($imageSize[0], $imageSize[1], $maxWidth, $maxHeight);
		
		//Create and resize image from image source
		$result = $this->createRealImageFromImageSource($filetmp, $uploadfile, $ext, $size, $imageSize, $quality);
		
		return $result;
	} 
	
	/**
	* Create and resize image from image source.
	* @param $filetmp passes image data.
	* @param $uploadfile passes pathname of uploaded file.
	* @param $ext passes extension of image source.
	* @param $size passes calculated size of image source.
	* @param $imageSize passes size of image source.
	* @param $quality passes quality of image in jpeg format.
	* @return bool.
	*/
	public function createRealImageFromImageSource($filetmp, $uploadfile, $ext, $size = array(), $imageSize = array(), $quality)
	{
		$result = false;
		
		if(count($size) > 0 && count($imageSize) > 0 && $filetmp != '' && $uploadfile != '' && $ext != '')
		{
			//Pathname of created dummy image					
			$dstImage = imagecreatetruecolor($size[0], $size[1]);
			
			//If dummy image is created
			if($dstImage)
			{	
				//Creating of dummy image
				$srcImage = $this->createDummyImage($filetmp, $ext);
					
				//If dummy jpg image is created	
				if($srcImage)
				{	
					//If image is resized
					if(imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $size[0], $size[1], $imageSize[0], $imageSize[1]))
					{	
						$realImage = $this->createRealImage($dstImage, $ext, $uploadfile, $quality);
						
						//Create real image from dummy
						if($realImage)
						{	
							//Destroy image source
							if(imagedestroy($dstImage))
							{	
								$result = true;
							}
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	/*
	* Create dummy image from image source.
	* @param $filetmp passes image data.
	* @param $ext passes extension of image source.
	* @return bool.
	*/
	public function createDummyImage($filetmp, $ext)
	{
		$result = false;
		
		//Create image dummy by its extension
		switch($ext)
		{
			case 'jpg':
			$result = imagecreatefromjpeg($filetmp);
			break;
			
			case 'jpeg':
			$result = imagecreatefromjpeg($filetmp);
			break;
			
			case 'png':
			$result = imagecreatefrompng($filetmp);
			break;
			
			case 'gif':
			$result = imagecreatefromgif($filetmp);
			break;
			
			default:
			$result = imagecreatefromjpeg($filetmp);
		}
		
		return $result;
	}
	
	/*
	* Create real image from image source.
	* @param $filetmp passes image data.
	* @param $ext passes extension of image source.
	* @param $uploadfile passes pathname of uploaded file.
	* @param $quality passes quality of jpeg image.
	* @return bool.
	*/
	public function createRealImage($dstImage, $ext, $uploadfile, $quality)
	{
		$result = false;
		
		//Create real image by its extension
		switch($ext)
		{
			case 'jpg':
			$result = imagejpeg($dstImage, $uploadfile, $quality);
			break;
			
			case 'jpeg':
			$result = imagejpeg($dstImage, $uploadfile, $quality);
			break;
			
			case 'png':
			$result = imagepng($dstImage, $uploadfile);
			break;
			
			case 'gif':
			$result = imagegif($dstImage, $uploadfile);
			break;
			
			default:
			$result = imagejpeg($dstImage, $uploadfile, $quality);
		}
		
		return $result;
	}
}
