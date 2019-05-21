<?php
namespace backend\models;

use Yii;
use common\models\Menu;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $meta_tags
 * @property string $credated_at
 * @property string $updated_at
 */
class Content extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 0;
	public $url;
	
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
    public function rules()
    {
        return [
            [['title', 'content', 'style'], 'required'],
            [['title', 'content', 'style'], 'string', 'min' => 2, 'message' => Yii::t('form', 'В этом поле минимально допустимое количество символов 2!')],
            [['controller_id', 'status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'meta_title'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'title' => Yii::t('form', 'Заголовок'),
            'content' => Yii::t('form', 'Текст'),
            'style' => Yii::t('form', 'CSS'),
            'meta_tags' => Yii::t('form', 'Мета теги'),
            'publish' => Yii::t('form', 'Публикация'),
            'created_at' => Yii::t('form', 'Создан'),
            'updated_at' => Yii::t('form', 'Обновлен'),
        ];
    }
    
    /**
     * Relation with content table by content_id field
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['content_id' => 'id']);
    }
    
    public function getImageurl()
	{
		return \Yii::$app->request->BaseUrl.'/_layout/images/icons/icon-download.png';
	}
	
	public static function getContentList()
	{
		return self::find()
		->select('menu.url, content.title, content.content, content.style')
		->leftJoin('menu', 'menu.content_id = content.id')
		->where('content.status > 0')
		->all();
	}
}
