<?php
namespace common\components;

class Settings {
	public static function getParams( $config ) {
		return (isset(\Yii::$app->params[$config])) ? \Yii::$app->params[$config] : null;
	}
}
