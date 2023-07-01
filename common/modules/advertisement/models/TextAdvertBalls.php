<?php

namespace common\modules\advertisement\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "text_advert".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $advert_id
 * @property integer $balls
 * @property integer $created_at
 *
 */
class TextAdvertBalls extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'text_advert_balls';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
				'value' => function() { return date('U');},
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'advert_id', 'balls', 'created_at'], 'integer'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('form', 'ID'),
			'user_id' => Yii::t('form', 'ID партнера'),
			'advert_id' => Yii::t('form', 'ID объявления'),
			'balls' => Yii::t('form', 'Баллы'),
			'created_at' => Yii::t('form', 'Дата создания'),
		];
	}
}