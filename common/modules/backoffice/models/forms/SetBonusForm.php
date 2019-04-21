<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use common\modules\structure\models\Matrix;

/**
 * Set bonus form
 */
class SetBonusForm extends Model
{
	public $matrix;
	public $structure;
	public $bonus;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['structure', 'matrix', 'bonus'], 'required'],
            [['structure', 'matrix', 'bonus'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'structure' => Yii::t('form', 'Назначить структуру'),
            'matrix' => Yii::t('form', 'Назначить матрицу'),
            'bonus' => Yii::t('form', 'Назначить бонус')
        ];
    }
    
    public function setBonus($structureNumber, $id, $bonusesList)
    {
		$result = false;
		
        if(!$this->validate())
        {
			return false;
        }
		
		$bonusAmount = (isset($bonusesList[$this->bonus])) ? (int)$bonusesList[$this->bonus] : 0;
		
		if($bonusAmount > 0)
		{	
			$matrixModel = new Matrix();
			
			if($matrixModel->setBonus($id, $id, $structureNumber, $this->matrix, $this->bonus, $bonusAmount))
			{	
				$result = true;
			}
		}
		 
        return $result;
    }
}
