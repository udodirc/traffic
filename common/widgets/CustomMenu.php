<?php

namespace backend\widgets;

use Yii;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

class CustomMenu extends Menu
{
	protected function renderItem($item)
    {	
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
			
            return strtr($template, [
                '{url}' => Html::encode(\Yii::$app->request->BaseUrl.DIRECTORY_SEPARATOR.$item['url']),
                '{label}' => $item['label'],
                '{image}' => $item['image'],
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

            return strtr($template, [
                '{label}' => $item['label'],
            ]);
        }
    }
}
?>
