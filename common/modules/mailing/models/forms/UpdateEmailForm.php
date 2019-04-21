<?php
namespace common\modules\mailing\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;
use common\modules\mailing\models\Mailing;

/**
 * UpdateMailForm model
 */
class UpdateEmailForm extends Model
{
	public $country;
	public $status;
	public $top_leaders_count;
	public $partners_offset;
	public $partners_count;
	public $mailing;
	public $login;
	public $first_name;
	public $last_name;
	
	 /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['country'], 'string'],
			[['top_leaders_count', 'status', 'partners_offset', 'partners_count', 'mailing', 'login', 'first_name', 'last_name'], 'integer'],
		];
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country' => Yii::t('form', 'Страна'),
            'status' => Yii::t('form', 'Статус'),
            'top_leaders_count' => Yii::t('form', 'Кол-во рефералов'),
            'partners_offset' => Yii::t('form', 'Отступ партнеров'),
			'partners_count' => Yii::t('form', 'Кол-во партнеров'),
			'mailing' => Yii::t('form', 'Отказ от рассылки'),
			'login' => Yii::t('form', 'Логин'),
			'first_name' => Yii::t('form', 'Имя'),
			'last_name' => Yii::t('form', 'Фамилия'),
        ];
    }
	
	/**
     * Reserve places in matrix.
     *
     * @return boolean
    */
    public function updateFile()
    {
        if(!$this->validate())
        {	
			return false;
        }
        
        $result = false;
        
        $model = new Partners();
        
        $partnersList = $model->getPartnerListByEmailFilters($this->country, $this->status, $this->top_leaders_count, $this->partners_offset, $this->partners_count, $this->mailing, $this->login, $this->first_name, $this->last_name);
        $result = Mailing::updatePartnersListByFilters($partnersList, $this->login, $this->first_name, $this->last_name);
       
        return $result;
	}
}
