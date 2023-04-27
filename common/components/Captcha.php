<?php
namespace common\components;

class Captcha
{
	public static function isCaptchaAllowed($config)
	{
		return (isset(\Yii::$app->params['is_login_'.$config.'_allowed']) && isset(Yii::$app->params['captcha_site_key'])) ? \Yii::$app->params['is_login_'.$config.'_allowed'] : false;
	}
}