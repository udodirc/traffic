<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;

/**
 * Structure partner form
 */
class StructurePartnerForm extends Model
{
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
	
	public $id;
	public $sponsor_id;
	public $partner_id;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sponsor_id', 'partner_id'], 'required'],
            [['sponsor_id', 'partner_id'], 'integer'],
            [['sponsor_id'], 'checkSponsorID'],
            [['partner_id'], 'checkPartnerID'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sponsor_id' => Yii::t('form', 'ID спонсора'),
            'partner_id' => Yii::t('form', 'ID партнера')
        ];
    }
    
    public function checkSponsorID($attribute, $param)
    {
		$data = (new \yii\db\Query())
		->select('id')
		->from('partners')
		->where(['id' => $this->sponsor_id])
		->one();
		
		if($data['id'] == null)
		{
			$this->addError($attribute, Yii::t('form', 'Такого спонсора не существует!'));
		}
	}
	
	public function checkPartnerID($attribute, $param)
    {
		$data = (new \yii\db\Query())
		->select('id')
		->from('partners')
		->where(['id' => $this->partner_id])
		->one();
		
		if($data['id'] == null)
		{
			$this->addError($attribute, Yii::t('form', 'Такого партнера не существует!'));
		}
	}
    
    public function addPartner()
    {
        if(!$this->validate())
        {
			 return false;
        }
		
		$result = true;
		$model = new Partners();
        
		$result = $model->addPartnerInStructure($this->sponsor_id, $this->partner_id, 1);
        
        return $result;
    }
}
