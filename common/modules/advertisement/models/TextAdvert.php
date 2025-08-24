<?php

namespace common\modules\advertisement\models;

use Yii;
use common\modules\backoffice\models\Partners;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "text_advert".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $title
 * @property string $link
 * @property string $text
 * @property integer $balls
 * @property integer $created_at
 *
 * @property Partners $partner
 */
class TextAdvert extends \yii\db\ActiveRecord
{
	public $clickCount;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text_advert';
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
            [['title', 'link', 'text', 'balls'], 'required'],
            [['partner_id', 'balls', 'counter', 'status', 'deleted', 'created_at'], 'integer'],
            ['link', 'url'],
            [['title', 'link', 'text'], 'string', 'max' => 100],
            ['balls', 'checkBalls'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'partner_id' => Yii::t('form', 'ID партнера'),
            'title' => Yii::t('form', 'Заголовок'),
            'link' => Yii::t('form', 'Ссылка'),
            'text' => Yii::t('form', 'Описание'),
            'balls' => Yii::t('form', 'Баллы'),
            'created_at' => Yii::t('form', 'Дата создания'),
        ];
    }
    
    public function checkBalls()
    {
		$model = Partners::findOne($this->partner_id);
	    $textAdvertStructureBalls = (isset(\Yii::$app->params['text_advert_balls']))
		    ? Yii::$app->params['text_advert_structure_balls']
		    : 0;
		$balls = 'total_balls_'.$textAdvertStructureBalls;
		$totalBalls = (!is_null($model)) ? $model->$balls : 0;

		if($totalBalls <= 0)
 		{
			$this->addError('balls', Yii::t('form', 'У вас недостаточно баллов!'));
		}
		
		if($totalBalls < $this->balls)
 		{
			$this->addError('balls', Yii::t('form', 'У вас недостаточно баллов!'));
		}

	    if(($model->getTextAdvert()->sum('balls') + $this->balls) > $totalBalls)
	    {
		    $this->addError('balls', Yii::t('form', 'У вас недостаточно баллов!'));
	    }
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'partner_id']);
    }

	public function getAdvertBalls()
	{
		return $this->hasMany(TextAdvertBalls::class, ['advert_id' => 'id']);
	}

    public static function getAdvertsList($limit)
    {
		$sql = "SELECT * 
		FROM `text_advert` 
		WHERE `id` >= (
			SELECT FLOOR(MAX(`id`) * RAND()) FROM `text_advert`
		) 
		ORDER BY `id` LIMIT ".$limit;
		
		$connection = \Yii::$app->db;
		$result = $connection->createCommand($sql)->queryAll();
		
		return $result;
	}
}
