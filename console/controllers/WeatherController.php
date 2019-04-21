<?php
namespace console\controllers;
 
use yii\console\Controller;
 
/**
 * Weather controller
 */
class WeatherController extends Controller 
{
    public function actionIndex() 
    {
		$city_id = Yii::$app->params['weather_city']; // id города
		$data_file = Yii::$app->params['weather_city'].$city_id.'.xml'; // адрес xml файла 
		$xml = simplexml_load_file($data_file); // раскладываем xml на массив
		$city = $xml->fact->station;
		$temp = $xml->fact->temperature;
		$pic = $xml->fact->image;
		$type = $xml->fact->weather_type;

		// Если значение температуры положительно, для наглядности добавляем "+"
		if ($temp>0) {$temp = '+'.$temp;}
		Yii::$app->params['weather'] = $temp;
		echo $temp;
    }
}
