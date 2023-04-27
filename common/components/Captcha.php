<?php
namespace common\components;

class Captcha
{
	public static function isCaptchaAllowed($config)
	{
		if(isset(\Yii::$app->params['captcha_site_key'])){
			return (isset(\Yii::$app->params['is_'.$config.'_captcha_allowed'])) ? \Yii::$app->params['is_'.$config.'_captcha_allowed'] : false;
		}

		return false;
	}
}