<?php

namespace common\modules\tickets;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\tickets\controllers';

    public function init()
    {
        parent::init();
		
		if(\Yii::$app->id == 'app-frontend')
        {	
			if(isset(\Yii::$app->params['backofficeThemeLayout']))
			{
				$this->layoutPath = \Yii::getAlias(\Yii::$app->params['backofficeThemeLayout']);
			}
		}
        // custom initialization code goes here
    }
}
