<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property integer $controller_id
 * @property string  $title
 * @property string  $content
 * @property string  $meta_title
 * @property string  $meta_description
 * @property string  $meta_keywords
 * @property integer $status
 * @property string  $credated_at
 * @property string  $updated_at
 */
class Content extends \yii\db\ActiveRecord
{
	const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    
	public $content_id;
	public $menu_name;
	public $content_no_wysywig;
	public $content_no_wysywig_on;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content';
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
            [['title'], 'required'],
            [['content'], 'checkContent', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['title', 'content', 'content_no_wysywig', 'meta_description', 'meta_keywords'], 'string', 'min' => 2, 'message' => Yii::t('form', 'В этом поле минимально допустимое количество символов 2!')],
            [['controller_id', 'content_no_wysywig_on', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'meta_title'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
        ];
    }
    
    public function checkContent($attribute, $param)
    {	
		if($this->content == '' && $this->content_no_wysywig == '')
		{
			$this->addError($attribute, Yii::t('form', 'Заполните поле!'));
		}
		elseif($this->content_no_wysywig == '' && $this->content_no_wysywig_on > 0)
		{
			$this->addError($attribute, Yii::t('form', 'Заполните поле!'));
		}
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'controller_id' => Yii::t('form', 'Контроллер'),
            'title' => Yii::t('form', 'Заголовок'),
            'content' => Yii::t('form', 'Текст'),
            'content' => Yii::t('form', 'Контент'),
            'content_no_wysywig' => Yii::t('form', 'Контент без wysywig'),
            'meta_title' => Yii::t('form', 'Мета заголовок'),
            'meta_description' => Yii::t('form', 'Мета описание'),
            'meta_keywords' => Yii::t('form', 'Мета ключи'),
            'publish' => Yii::t('form', 'Публикация'),
            'created_at' => Yii::t('form', 'Создан'),
            'updated_at' => Yii::t('form', 'Обновлен'),
        ];
    }
    
    public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			
			if($this->content_no_wysywig_on > 0)
			{
				$this->content = $this->content_no_wysywig;
			}
	 
			return true;
		}
		return false;
	}
    
    /**
     * Relation with menu table by id field
     */
	public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'content_id']);
    }
    
    public function getContentDataByID($id)
    {
		return self::find()
		->select('menu.name AS menu_name, content.title, content.content, content.meta_title, content.meta_description, content.meta_keywords')
		->from('content')
		->leftJoin('menu', 'content.id = menu.content_id')
		->where('content.id=:id', [':id' => $id])
		->one();
	}
}
