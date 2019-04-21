<?php
namespace common\modules\structure\models\forms;

use Yii;
use yii\base\Model;
use common\modules\structure\models\Matrix;
use common\modules\structure\models\Matrix1;
use common\modules\backoffice\models\Partners;

/**
 * Reserve partner in matrix form by sposnor login
 */
class SetMatrixForm extends Model
{
	public $partner_id;
	public $sponsor_login;
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
			[['partner_id', 'sponsor_login', 'structure', 'matrix', 'status', 'places_count'], 'required'],
			[['partner_id', 'structure', 'matrix', 'status', 'places_count', 'root'], 'integer'],
			[['sponsor_login'], 'string'],
			['places_count', 'compare', 'compareValue' => 0, 'operator' => '>']
		];
	}
	
	public function checkActivation()
    {
		$partnerData = Partners::find()->select(['matrix'])->where(['id'=>$this->partner_id])->one();
		$sponsorData = Partners::find()->select(['matrix'])->where(['login'=>$this->sponsor_login])->one();
		
		if($partnerData == null)
		{	
			$this->addError('places_count', Yii::t('form', 'Такого парнера нет в базе!'));
		}
		else
		{
			if($partnerData->matrix == 0 )
			{	
				$this->addError('places_count', Yii::t('form', 'Партнер не активирован!'));
			}
		}
		
		if($sponsorData == null)
		{	
			$this->addError('places_count', Yii::t('form', 'Такого парнера нет в базе!'));
		}
		else
		{
			if($sponsorData->matrix == 0 )
			{	
				$this->addError('places_count', Yii::t('form', 'Спонсор не активирован!'));
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
            'sponsor_login' => Yii::t('form', 'Логин спонсора'),
            'status' => Yii::t('form', 'Статус'),
            'partner_id' => Yii::t('form', 'Партнер'),
			'places_count' => Yii::t('form', 'Кол-во мест в матрице'),
			'root' => Yii::t('form', 'Основной'),
        ];
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
        $sponsorData = Partners::find()->select(['id'])->where(['login'=>$this->sponsor_login])->one();
        
        if($sponsorData !== null)
		{	
			$root = ($this->root > 0) ? true : false;
			$result = $model->reservePlacesInStructure($sponsorData->id, $this->partner_id, $this->structure, $this->matrix, $this->status, $this->places_count, '', 0, '', $root, true);
		}
        
        return $result;
	}
}
