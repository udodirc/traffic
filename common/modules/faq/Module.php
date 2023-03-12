<?php

namespace common\modules\faq;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\faq\controllers';

    public function init()
    {
        parent::init();
        
        if(\Yii::$app->id == 'app-frontend')
        {	
			if(isset(\Yii::$app->params['backofficeThemeLayout']))
			{
				if(!\Yii::$app->user->isGuest) {
					$this->layoutPath = \Yii::getAlias( \Yii::$app->params['backofficeThemeLayout'] );
				}
			}
		}

        // custom initialization code goes here
    }
}
