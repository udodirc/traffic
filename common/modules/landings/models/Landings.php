<?php

namespace common\modules\landings\models;

use Yii;
use common\components\FileHelper;
use common\components\Zip;

/**
 * This is the model class for table "landings".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 */
class Landings extends \yii\db\ActiveRecord
{
	public $file; 
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'landings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['styles', 'js', 'inner_style', 'inner_js', 'body'], 'string'],
            [['title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'title' => Yii::t('form', 'Заголовок'),
            'styles' => Yii::t('form', 'Подключение css'),
            'js' => Yii::t('form', 'Подключение js'),
            'inner_style' => Yii::t('form', 'Внутрений стиль'),
            'inner_js' => Yii::t('form', 'Внутрений js'),
            'body' => Yii::t('form', 'Body'),
        ];
    }
    
    public function afterSave($insert, $changedAttributes)
	{
		if(Landings::createAssetsFiles($this->id))
		{	
			$tmpDir = (isset(Yii::$app->params['upload_dir']['landings']['tmp_dir'])) ? \Yii::getAlias('@landings').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['tmp_dir'] : '';
			$zipDir = (isset(Yii::$app->params['upload_dir']['landings']['zip'])) ? \Yii::getAlias('@landings').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['zip'] : '';
			FileHelper::removeAllFilesInDir($tmpDir);
			FileHelper::removeAllFilesInDir($zipDir);
			
			return true;
		}
		
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function afterDelete()
    {
		if((isset(Yii::$app->params['upload_dir']['landings']['dir']) && Yii::$app->params['upload_dir']['landings']['dir'] != ''))
		{
			$dir = \Yii::getAlias('@landings-assets').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['dir'].DIRECTORY_SEPARATOR.$this->id;
			
			if(is_dir($dir)) 
			{
				if(FileHelper::removeAllFilesInDir($dir, true))
				{
					return true;
				}
			}
		}
         
        return false;
        
		parent::afterDelete();
    }
    
    public static function unZipFiles($filePath, $file)
    {
		$result = false;
		
		if(file_exists($filePath))
		{	
			$extractTo = (isset(Yii::$app->params['upload_dir']['landings']['tmp_dir'])) ? \Yii::getAlias('@landings').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['tmp_dir'] : '';
			
			if($extractTo != '' && is_dir($extractTo))
			{
				$zip = new Zip($filePath, $file, $extractTo);
				$result = $zip->extractZipTo();
			}
		}
		
		return $result;
	}
	
	public static function createDir($dir, $id)
    {
		$result = false;
		
		if(($dir != ''))
		{
			if(!is_dir($dir))
			{
				if(!mkdir($dir, 0777, true))
				{	
					return false;
				}
			}
				
			if(is_dir($dir))
			{
				$result = true;
			}
		}
		
		return $result;
	}
	
	public static function createInnerStyle($id)
    {
		$result = false;
		
		if($id > 0)
		{	
			$dir = (isset(Yii::$app->params['upload_dir']['landings']['dir'])) ? \Yii::getAlias('@landings').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['dir'] : '';
			
			if(($dir != ''))
			{
				$landingDir = $dir.DIRECTORY_SEPARATOR.$id;
				
				if(self::createDir($landingDir, $id))
				{	
					$dir = Landings::getSourcePath('assets_path', $id);
					
					if(self::createDir($dir, $id))
					{	
						$dir = Landings::getSourcePath('css_path', $id);
						
						if(self::createDir($dir, $id))
						{	
							$result = true;
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function createAssetsFiles($id)
    {
		$result = false;
		
		if($id > 0)
		{	
			$dir = (isset(Yii::$app->params['upload_dir']['landings']['dir'])) ? \Yii::getAlias('@landings').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['dir'] : '';
			$tmpDir = (isset(Yii::$app->params['upload_dir']['landings']['tmp_dir'])) ? \Yii::getAlias('@landings').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['tmp_dir'] : '';
			$zipDir = (isset(Yii::$app->params['upload_dir']['landings']['zip'])) ? \Yii::getAlias('@landings').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['zip'] : '';
			
			if(($dir != '' && is_dir($dir)) && ($tmpDir != '' && is_dir($tmpDir)))
			{	
				$files = FileHelper::findFiles($zipDir);
				
				foreach($files as $i=>$filePath)
				{
					$file = explode('/', $filePath);
					$file = end($file);
					
					if(self::unZipFiles($filePath, $file))
					{	
						if(self::copyZipFiles($id, $file, $tmpDir, $dir))
						{	
							$result = true;
						}
						else
						{
							$result = false;
							break;
						}
					}
					else
					{
						$result = false;
						break;
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function copyZipFiles($id, $file, $tmpDir,$dir)
    {
		$result = false;
		$landingDir = $dir.DIRECTORY_SEPARATOR.$id;
				
		if(!is_dir($landingDir))
		{
			if(!FileHelper::createDirectory($landingDir))
			{	
				return false;
			}
		}
					
		FileHelper::copyDirectory($tmpDir, $landingDir, []);
		$dirName = explode('.', $file);
		$dirName = $dirName[0];
				
		if(is_dir($landingDir.DIRECTORY_SEPARATOR.$dirName))
		{
			$result = true;
		}
		
		return $result;
	}
	
	public static function getSourcePath($dirName, $id = 0)
    {
		$result = '';
		
		switch($dirName)
		{
			case 'css_path':
				
				if((isset(Yii::$app->params['upload_dir']['landings']['dir']) && Yii::$app->params['upload_dir']['landings']['dir'] != '') && ((isset(Yii::$app->params['upload_dir']['landings']['assets']) && Yii::$app->params['upload_dir']['landings']['assets'] != '')) && ((isset(Yii::$app->params['upload_dir']['landings']['dir']) && Yii::$app->params['upload_dir']['landings']['css'] != '')))
				{
					$result = \Yii::getAlias('@web').DIRECTORY_SEPARATOR.\Yii::getAlias('@admin_uploads').DIRECTORY_SEPARATOR.'landings'.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['dir'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['assets'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['css'];
				}
				
			break;
				
			case 'assets_path':
				
				if((isset(Yii::$app->params['upload_dir']['landings']['dir']) && Yii::$app->params['upload_dir']['landings']['dir'] != '') && ((isset(Yii::$app->params['upload_dir']['landings']['assets']) && Yii::$app->params['upload_dir']['landings']['assets'] != '')) != '')
				{
					$result = \Yii::getAlias('@landings-assets').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['dir'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['assets'];
				}
				
			break;
				
			case 'images_path':
				
				if((isset(Yii::$app->params['upload_dir']['landings']['dir']) && Yii::$app->params['upload_dir']['landings']['dir'] != '') && ((isset(Yii::$app->params['upload_dir']['landings']['images']) && Yii::$app->params['upload_dir']['landings']['images'] != '')) != '')
				{
					$result = \Yii::getAlias('@landings-assets').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['dir'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['images'];
				}
				
			break;
		}
		
		return $result;
	}
	
	public static function parseTags($id, $body, $partnersModel)
    {
		$tag = [];
		$tagsList = self::getTagsList($id, $partnersModel);
		$result = preg_replace_callback('/\{([^}]+)\}/', function($tagData) use ($tagsList)
		{	
			if(isset($tagData[1]))
			{	
				$output = '';
				$tag = explode('=', $tagData[1]);
					
				if(isset($tagsList[$tag[0]]))
				{	
					switch($tag[0])
					{
						case 'images':
							$output = $tagsList[$tag[0]].DIRECTORY_SEPARATOR.$tag[1];
						break;
							
						default:
							$output = $tagsList[$tag[0]];
						break;
					}
					
					return  $output; // Then replace it from our array
				}
			}
			
		}, $body);
		
		return $result;
	}
	
	public static function getTagsList($id, $partnersModel)
    {
		return [
			'images'=>
			(((isset(Yii::$app->params['upload_dir']['landings']['dir']) && Yii::$app->params['upload_dir']['landings']['dir'] != '') && ((isset(Yii::$app->params['upload_dir']['landings']['images']) && Yii::$app->params['upload_dir']['landings']['images'] != '')))
			? \Yii::getAlias('@web').DIRECTORY_SEPARATOR.\Yii::getAlias('@admin_uploads').DIRECTORY_SEPARATOR.'landings'.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['dir'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir']['landings']['images']
			: ''),
			'login'=> $partnersModel->login,
			'first_name'=> $partnersModel->first_name,
			'last_name'=> $partnersModel->last_name,
			'register_date'=> date("Y-m-d", $partnersModel->created_at),
		];
	}
}
