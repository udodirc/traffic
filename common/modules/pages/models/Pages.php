<?php

namespace common\modules\pages\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property integer $name
 * @property integer $url
 * @property string $body
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body', 'name', 'url'], 'required'],
            [['body', 'name', 'url'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'name' => Yii::t('form', 'Название'),
            'body' => Yii::t('form', 'Контент'),
        ];
    }
}
