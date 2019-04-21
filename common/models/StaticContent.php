<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "static_content".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property integer $active
 */
class StaticContent extends \yii\db\ActiveRecord
{
	public $content_no_wysywig;
	public $content_no_wysywig_on;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static_content';
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
            [['name'], 'required'],
            [['content'], 'checkContent', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['name', 'content', 'content_no_wysywig'], 'string', 'min' => 2, 'message' => Yii::t('form', 'В этом поле минимально допустимое количество символов 2!')],
            [['status', 'content_no_wysywig_on'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
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
            'name' => Yii::t('form', 'Имя'),
            'content' => Yii::t('form', 'Контент'),
            'content_no_wysywig' => Yii::t('form', 'Контент без wysywig'),
            'status' => Yii::t('form', 'Статус'),
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
    
    public static function getStaticContentByName($contentArr, $name, $tags = false)
    {
		return isset($contentArr[$name]) ? ($tags) ? strip_tags($contentArr[$name])  : $contentArr[$name] : '';
	}
	
	public static function getStaticContentList($contentList)
    {
		return ArrayHelper::map(self::find()->where(['name'=>$contentList])->asArray()->all(), 'name', 'content');
	}
}
