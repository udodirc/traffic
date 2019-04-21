<?php

namespace common\modules\messages\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $reply
 * @property integer $created_at
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }
    
    public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
			],
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'reply', 'created_at'], 'required'],
            [['text'], 'string'],
            [['reply', 'created_at'], 'integer'],
            [['title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'title' => Yii::t('form', 'Title'),
            'text' => Yii::t('form', 'Text'),
            'reply' => Yii::t('form', 'Reply'),
            'created_at' => Yii::t('form', 'Created At'),
        ];
    }
}
