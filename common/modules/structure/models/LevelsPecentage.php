<?php

namespace common\modules\structure\models;

use Yii;

/**
 * This is the model class for table "levels_pecentage".
 *
 * @property integer $level
 * @property integer $value
 */
class LevelsPecentage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'levels_pecentage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'value'], 'required'],
            [['level', 'value'], 'integer'],
            [['level'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'level' => Yii::t('form', 'Level'),
            'value' => Yii::t('form', 'Value'),
        ];
    }
}
