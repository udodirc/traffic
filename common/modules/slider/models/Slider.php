<?php

namespace common\modules\slider\models;

use Yii;
use common\modules\uploads\models\Files;

/**
 * This is the model class for table "slider".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 */
class Slider extends \yii\db\ActiveRecord
{
	public $file;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['title', 'content'], 'string', 'max' => 255]
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
        ];
    }
    
    public function afterSave($insert, $changedAttributes)
	{
		$result = Files::uploadImageFromTmpDir('slider', $this->id, false, true);
		
		parent::afterSave($insert, $changedAttributes);
	}
}
