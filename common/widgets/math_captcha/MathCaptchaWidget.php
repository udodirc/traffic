<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class MathCaptchaWidget extends Widget
{
	public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {   
		$params = \Yii::$app->params['captcha_params'];
        return $this->getCaptchaImage($params);
    } 
    
    public function getCaptchaImage($params)
    {
		$result = null;
		
		if(isset($params))
		{
			$image = imagecreate($params['width'], $params['height']);
		
			if($image)
			{
				$background = imagecolorallocate($image, $params['bg_color'][0], $params['bg_color'][1], $params['bg_color'][2]);
			
				if($background)
				{
					$textColor = imagecolorallocate($image, $params['text_color'][0], $params['text_color'][1], $params['text_color'][2]);
				
					if($textColor)
					{
						$number1 = rand(1,10); //Generate First number between 1 and 6 
						$number2 = rand(1,10); //Generate Second number between 5 and 9 
						$_SESSION["math_captcha_answer"] = $number1 + $number2;
					
						if(imagestring($image, $params['font_size'], $params['x'], $params['y'], ($number1.' + '.$number2), $textColor))
						{
							$result = imagejpeg($image, null, 80);
						}
					}
				}
			}
		}
		
		return $result;
	}
}
