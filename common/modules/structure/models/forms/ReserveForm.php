<?php
namespace common\modules\structure\models\forms;

use Yii;
use yii\base\Model;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\Matrix1;
use common\modules\backoffice\models\Partners;

/**
 * Reserve partner in matrix form
 */
class ReserveForm extends Model
{
	public $partner_id;
	public $structure;
	public $matrix;
	public $status;
	public $places_count;
	public $root;
	
	 /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['partner_id', 'structure', 'matrix', 'status', 'places_count'], 'required'],
			[['partner_id', 'structure', 'matrix', 'status', 'places_count', 'root'], 'integer'],
			['places_count', 'compare', 'compareValue' => 0, 'operator' => '>'],
			['places_count', 'checkActivation'],
			['places_count', 'checkTotalCountPlaces', 'except' => 'backend'],
		];
	}
	
	public function checkActivation()
    {
		$data = Partners::find()->select(['matrix_1'])->where(['id'=>$this->partner_id])->asArray()->one();
		
		if(empty($data))
		{	
			$this->addError('places_count', Yii::t('form', 'Партнер не активирован!'));
		}
		else
		{
			if($data['matrix_1'] == 0)
			{	
				$this->addError('places_count', Yii::t('form', 'Партнер не активирован!'));
			}
		}
	}
	
	public function checkTotalCountPlaces()
    {
		$maxReservePlaces = (isset(Yii::$app->params['max_reserve_places'])) ? Yii::$app->params['max_reserve_places'] : 0;
		
		if($maxReservePlaces > 0)
		{
			if($this->places_count > $maxReservePlaces)
			{	
				$this->addError('places_count', Yii::t('form', 'Превышенно количество мест!'));
			}
			else
			{
				$data = Matrix::getCountPlacesFromMatrixByPartnerID($this->structure, $this->matrix, $this->partner_id);
				
				if(($data + $this->places_count) > $maxReservePlaces)
				{	
					$this->addError('places_count', Yii::t('form', 'Превышенно количество мест!'));
				}
			}
		}
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'structure' => Yii::t('form', 'Структура'),
            'matrix' => Yii::t('form', 'Матрица'),
            'status' => Yii::t('form', 'Статус'),
            'partner_id' => Yii::t('form', 'Партнер'),
			'places_count' => Yii::t('form', 'Кол-во мест в матрице'),
			'root' => Yii::t('form', 'Основной'),
        ];
    }
    
    /**
     * Scenarios
     *
     * @return mixed
     */
    public function scenarios()
	{
		$scenarios = parent::scenarios();
		
        $scenarios['backend'] = ['partner_id', 'structure', 'matrix', 'status', 'places_count', 'root'];
		
		$scenarios['front'] = ['partner_id', 'structure', 'matrix', 'places_count'];
		
		return $scenarios;
    }
    
    public function validateData($model)
    {
        if(!$this->validate())
        {	
			return false;
        }
        
        return true;
	}
    
    /**
     * Reserve places in matrix.
     *
     * @return boolean
    */
    public function reserve($model)
    {
        if(!$this->validate())
        {	
			return false;
        }
        
        $result = false;
        $model = new Matrix();
        
        if(($partnerModel = Partners::findOne($this->partner_id)) !== null) 
        {
			$root = ($this->root > 0) ? true : false;
			$result = $model->reservePlacesInStructure($partnerModel->sponsor_id, $this->partner_id, $this->structure, $this->matrix, $this->status, $this->places_count, '', 0, '', $root);
		}
		
        return $result;
	}
}
