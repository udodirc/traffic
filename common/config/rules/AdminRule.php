<?php

namespace common\config\rules;

use yii\rbac\Rule;

class AdminRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'admin';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return \Yii::$app->user->id==$user;
    }
}