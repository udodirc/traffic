<?php

namespace frontend\widgets;

use Yii;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

class CustomMenu extends Menu
{
	public $submenuTemplate  = "\n<ul class='dropdown'>\n{items}\n</ul>\n";

	protected function renderItem($item)
    {	
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
			
            return strtr($template, [
                '{url}' => Html::encode(\Yii::$app->request->BaseUrl.DIRECTORY_SEPARATOR.$item['url'][0]),
                '{label}' => $item['label'],
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
