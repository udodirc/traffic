<?php
namespace common\modules\structure\models\forms;

use Yii;
use yii\base\Model;
use common\modules\structure\models\Matrix;

/**
 * Activate form
 */
class ActivateForm extends Model
{
	public $structure;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['structure'], 'required'],
            [['structure'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'structure' => Yii::t('form', 'Структура'),
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function activatePartner($sponsorID, $id, $pay)
    {
		$result = false;
		
        if(!$this->validate())
        {	
			return $result;
        }
        
		if($id > 0 && $sponsorID > 0) 
        {
			$model = new Matrix();
			$pay = ($pay > 0) ? true : false;
			$result = $model->addPartnerInStructure($sponsorID, $id, 1, 1, '', false, 2, false, false, $pay);
		}
      
        return $result;
	}
}
