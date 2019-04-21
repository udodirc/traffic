<?php
namespace common\modules\uploads;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\uploads\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->setAliases([
            //'@uploads-assets' => __DIR__ . '/assets'
        ]);
    }
}
