<?php
namespace common\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;

class CustomUrlRule extends Object implements UrlRuleInterface
{
	public function createUrl($manager, $route, $params)
    {
		var_dump($route);
		if ($route === 'menu/test') {
          
        }
        return false;  // данное правило не применимо
    }

    public function parseRequest($manager, $request)
    {
		$pathInfo = $request->getPathInfo();
        
        if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
            // Ищем совпадения $matches[1] и $matches[3] 
            // с данными manufacturer и model в базе данных
            // Если нашли, устанавливаем $params['manufacturer'] и/или $params['model']
            // и возвращаем ['car/index', $params]
        }
        return false;  // данное правило не применимо
    }
}
