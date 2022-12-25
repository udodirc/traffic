<?php
namespace common\components;

use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

class ContentHelper
{
	public static function checkContentVeiables($content)
	{
		$varList = self::getVariablesList();
		$result = str_replace(array_keys($varList), array_values($varList), $content);
		
		return $result;
	}
	
	public static function getVariablesList()
	{
		return [
			'{site}' => Url::base(true),
			'{base_url}' => \Yii::$app->request->BaseUrl,
			'{id}' => (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0,
			'{login}' => (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->login : '',
			'{first_name}' => (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->firstName : '',
			'{last_name}' => (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->lastName : '',
			'{register_date}' => (!is_null(\Yii::$app->user->identity)) ? date("Y-m-d H:i:s", \Yii::$app->user->identity->registerDate) : '',
			'{activation_button}' => \Yii::$app->controller->renderPartial('partial/activation_button'),
		];
	}

    public static function outPutContent($content)
    {
        $htmlPurifier = (isset(\Yii::$app->params['html_purifier'])) ? \Yii::$app->params['html_purifier'] : false;

        return ($content->content != null)
            ? ($htmlPurifier) ? HtmlPurifier::process($content->content) : $content->content
            : '';
    }
}
