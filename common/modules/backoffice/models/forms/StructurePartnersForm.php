<?php
namespace common\modules\backoffice\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\modules\backoffice\models\Partners;
use common\modules\structure\models\Matrix;

/**
 * Structure partners form
 */
class StructurePartnersForm extends Model
{
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    
	public $count;
	public $sponsor_login;
    public $login;
    
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sponsor_login', 'login', 'count'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            [['count'], 'integer'],
            ['sponsor_login', 'exist', 'targetClass' => '\common\modules\backoffice\models\Partners', 'targetAttribute' => 'login', 'message' => 'Такого пользователя не сушествует!'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'sponsor_login' => Yii::t('form', 'Логин спонсора'),
            'login' => Yii::t('form', 'Логин'),
            'count' => Yii::t('form', 'Количество партнеров')
        ];
    }
    
    public function addPartners()
    {
        if(!$this->validate())
        {
			 return false;
        }
		
		$result = false;
		$model = new Matrix();
		$data = Partners::find()->select(['id'])->where(['login'=>$this->sponsor_login])->one();
        $structureNumber = 1;
        
        if(!is_null($data))
        {
			$sposnorID = $data->id;
			
			if($sposnorID > 0)
			{
				$model = new Partners();
				
				for($i=1; $i <= $this->count; $i++)
				{
					$j = self::checkExistData($this->login, $i);
					$login = $this->login.$j;
					
					$model->sponsor_id = $sposnorID;
					$model->login = $login;
					$model->first_name = $login;
					$model->last_name = $login;
					$model->email = $login.'@mail.com';
					$model->phone = '';
					$model->group_id = 0;
					$model->status = self::STATUS_ACTIVE;
					$model->created_at = 0;
					$model->setPassword('123456');
					$model->generateAuthKey();
					
					$result = $model->registerPartner($structureNumber, $model, false);
					
					if(!$result)
					{
						break;
					}
				}
			}
		}
		
        return $result;
    }
    
    public function checkExistData($login, $number)
    {
		$data = Partners::find()->select(['id'])->where('login = :login OR email = :email', [':login'=>$this->login.$number, ':email'=>$this->login.$number.'@mail.com'])->one();
		
		if(!is_null($data) && $data->id > 0)
		{
			$number++;
			$result = self::checkExistData($login, $number);
		}
		else
		{
			$result = $number;
		}
		
		return $result;
	}
}
