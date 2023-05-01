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

	public static function getScenario($config)
	{
		if(isset(\Yii::$app->params['captcha_site_key']) && isset(\Yii::$app->params['is_'.$config.'_captcha_allowed'])){
			return (\Yii::$app->params['is_'.$config.'_captcha_allowed']) ? 'with_captcha' : 'without_captcha';
		}

		return 'without_captcha';
	}
}