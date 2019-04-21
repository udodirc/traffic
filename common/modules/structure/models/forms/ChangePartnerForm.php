<?php
namespace common\modules\structure\models\forms;

use Yii;
use yii\base\Model;
use common\modules\structure\models\Matrix;
use common\modules\backoffice\models\Partners;

/**
 * Change partner form
 */
class ChangePartnerForm extends Model
{
	public $login;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            [['login'], 'string'],
            [['login'], 'checkIsExistPartner', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('form', 'Логин'),
        ];
    }
    
    public function checkIsExistPartner($attribute, $param)
    {
		$data = Partners::find()->where('login = :login', [':login'=>$this->login])->one();
		
		if($data == null)
		{
			$this->addError($attribute, Yii::t('form', 'Такого партнера нет!'));
		}
		else
		{
			if($data->matrix_1 <= 0)
			{
				$this->addError($attribute, Yii::t('form', 'Партнер не активен!'));
			}
		}
	}
    
    /**
     * Change partner in structure.
     *
     * @return bool
     */
    public function changePartner($id, $structure, $number, $demo)
    {
		$result = false;
		
        if(!$this->validate())
        {	
			return $result;
        }
        
		if($id > 0 && $structure > 0 && $number > 0) 
        {	
			if(($data = Partners::find()->where('login = :login', [':login'=>$this->login])->one()) !== null)
			{	
				$matrixModel = new Matrix();
				$result = $matrixModel->changeAdmin($data->id, $id, $structure, $number, $demo);
			}
		}
      
        return $result;
	}
}
