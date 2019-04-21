<?php
namespace common\modules\news\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $author_id
 * @property string $title
 * @property string $short_text
 * @property string $text
 * @property string  $meta_title
 * @property string  $meta_description
 * @property string  $meta_keywords
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 */
class News extends \yii\db\ActiveRecord
{
	public $username;
	
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_text', 'text'], 'required'],
            [['author_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['short_text', 'text', 'meta_description', 'meta_keywords'], 'string'],
            [['title', 'meta_title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'author_id' => Yii::t('form', 'Автор'),
            'title' => Yii::t('form', 'Заголовок'),
            'short_text' => Yii::t('form', 'Анонс'),
            'text' => Yii::t('form', 'Текст'),
            'meta_title' => Yii::t('form', 'Мета заголовок'),
            'meta_description' => Yii::t('form', 'Мета описание'),
            'meta_keywords' => Yii::t('form', 'Мета ключи'),
            'status' => Yii::t('form', 'Статус'),
            'created_at' => Yii::t('form', 'Создан'),
            'updated_at' => Yii::t('form', 'Отредактирован'),
        ];
    }
    
    /**
     * Add menu id before user insert or update
     *
     * @return mixed
     */
    public function beforeSave($insert) 
    {
		if(parent::beforeSave($insert)) 
		{	
			if($this->isNewRecord) 
			{	
				$this->author_id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
			}
		}
		
		return parent::beforeSave($insert);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
    
    public function getAuthorName()
	{
		$author = $this->author;
 
		return $author ? $author->username : '';
	}
    
    public function getNewsList()
    {
		return self::find()
		->select('user.username AS author, news.id, news.title, news.status, news.created_at, news.updated_at')
		->leftJoin('user', 'user.id = news.author_id');
	}
}
