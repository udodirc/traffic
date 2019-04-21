<?php

namespace common\config\rules;

use yii\rbac\Rule;

class ModeratorRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'moderator';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return \Yii::$app->user->id==$user;
    }
}
