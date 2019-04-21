<?php

namespace common\modules\seo\models;

use Yii;

/**
 * This is the model class for table "seo".
 *
 * @property string $name
 * @property string $meta_title
 * @property string $meta_desc
 * @property string $meta_keywords
 */
class Seo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'meta_title', 'meta_desc', 'meta_keywords'], 'required'],
            [['meta_title', 'meta_desc', 'meta_keywords'], 'string'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('form', 'Имя'),
            'meta_title' => Yii::t('form', 'Мета заголовок'),
            'meta_desc' => Yii::t('form', 'Мета описание'),
            'meta_keywords' => Yii::t('form', 'Мета ключи'),
        ];
    }
}
