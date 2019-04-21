<?php

namespace common\modules\faq\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property integer $id
 * @property string $question
 * @property string $answer
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'question', 'answer'], 'required'],
            [['type'], 'integer'],
            [['answer'], 'string'],
            [['question'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'type' => Yii::t('form', 'Тип'),
            'question' => Yii::t('form', 'Вопрос'),
            'answer' => Yii::t('form', 'Ответ'),
        ];
    }
}
