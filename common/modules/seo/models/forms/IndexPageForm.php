<?php
namespace common\modules\seo\models\forms;

use Yii;
use yii\base\Model;
use common\models\Settings;

/**
 * Index page form
 */
class IndexPageForm extends Model
{
	public $meta_tags;
	public $meta_keywords;
	public $title;
	
	/**
     * @inheritdoc
    */
    public function rules()
    {
        return [
			[['meta_tags', 'meta_keywords', 'title'], 'string'],
        ];
	}
	
	/**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'meta_tags' => Yii::t('form', 'Meta tags'),
			'meta_keywords' => Yii::t('form', 'Meta keywords'),
			'title' => Yii::t('form', 'Title'),
        ];
    }
    
    public function save($update = false)
    {
        if(!$this->validate())
        {	
			return null;
        }
        
        $result = null;
        
        if($update)
        {
			$result = \Yii::$app->db->createCommand()
            ->update('settings', ['value' => $this->title], ['name'=>'seo', 'index'=>'title'])
            ->execute();
            
			$result = \Yii::$app->db->createCommand()
			->update('settings', ['value' => $this->meta_tags], ['name'=>'seo', 'index'=>'meta_tags'])
			->execute();
				
			$result = \Yii::$app->db->createCommand()
			->update('settings', ['value' => $this->meta_keywords], ['name'=>'seo', 'index'=>'meta_keywords'])
			->execute();
		}
		else
		{
			$valuesArray = [
				['seo', 'title', $this->title],
				['seo', 'meta_tags', $this->meta_keywords],
				['seo', 'meta_keywords', $this->meta_keywords],
			];
			$result = \Yii::$app->db->createCommand()->batchInsert('settings', ['name', 'index', 'value'], $valuesArray)->execute();
		}
              
        return $result;
	}
}
