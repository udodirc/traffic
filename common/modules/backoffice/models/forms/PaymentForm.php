<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;
use common\modules\structure\models\Matrix;
use common\models\DbBase;

/**
 * Signup form
 */
class PaymentForm extends Model
{
	public $count;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['count'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
			['count', 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
			'count' => Yii::t('form', 'Количество мест в 1-ой платформе'),
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function activate($id, $type)
    {
        if(!$this->validate())
        {	
			return false;
        }
        
        \Yii::$app->session->set('activation.partner_id', $id);
        \Yii::$app->session->set('activation.count', $this->count);
        \Yii::$app->session->set('activation.payment_type', $type);
        
        if(\Yii::$app->session->get('activation.partner_id') || \Yii::$app->session->get('activation.count') || \Yii::$app->session->get('activation.payment_type'))
		{
			return true;
		}
    }
}
