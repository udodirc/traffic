<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use backend\models\Settings;

/**
 * Payments settings form
 */
class PaymentsSettingsForm extends Model
{
	public $is_activation_allowed;
	public $is_payment_allowed;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_activation_allowed', 'is_payment_allowed'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'is_activation_allowed' => Yii::t('form', 'Включить оплату'),
            'is_payment_allowed' => Yii::t('form', 'Включить выплаты'),
        ];
    }
    
    public function setSettings()
    {
		$result = false;
		
        if(!$this->validate())
        {
			return false;
        }
        
        $result = \Yii::$app->db->createCommand("UPDATE `settings` SET `value` = '".$this->is_activation_allowed."' WHERE `name`='is_activation_allowed'")
		->execute();
        $result = \Yii::$app->db->createCommand("UPDATE `settings` SET `value` = '".$this->is_payment_allowed."' WHERE `name`='is_payment_allowed'")
		->execute();
        
        return $result;
    }
}
