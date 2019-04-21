<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use backend\models\Settings;

/**
 * Modules settings form
 */
class ModulesSettingsForm extends Model
{
	public $is_feedbacks_allowed;
	public $is_tickets_allowed;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_feedbacks_allowed', 'is_tickets_allowed'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'is_feedbacks_allowed' => Yii::t('form', 'Включить модуль отзывов'),
            'is_tickets_allowed' => Yii::t('form', 'Включить модуль тикетов'),
        ];
    }
    
    public function setSettings()
    {
		$result = false;
		
        if(!$this->validate())
        {
			return false;
        }
        
        $result = \Yii::$app->db->createCommand("UPDATE `settings` SET `value` = '".$this->is_feedbacks_allowed."' WHERE `name`='is_feedbacks_allowed'")
		->execute();
        $result = \Yii::$app->db->createCommand("UPDATE `settings` SET `value` = '".$this->is_tickets_allowed."' WHERE `name`='is_tickets_allowed'")
		->execute();
        
        return $result;
    }
}
