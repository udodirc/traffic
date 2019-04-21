<?php
namespace common\modules\structure\models\forms;

use Yii;
use yii\base\Model;
use common\modules\backoffice\models\Partners;
use common\models\DbBase;

/**
 * Transfer balls form
 */
class TransferBallsForm extends Model
{
	public $sender_login;
	public $receiver_login;
	public $balls;
	public $sender_id;
	public $receiver_id;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_login', 'receiver_login', 'balls'], 'required'],
            [['sender_login', 'receiver_login'], 'string'],
            [['balls'], 'integer'],
            ['sender_login', 'isExistSenderLogin'],
            ['receiver_login', 'isExistReceiverLogin'],
            ['balls', 'compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'balls' => Yii::t('form', 'Баллы'),
            'sender_login' => Yii::t('form', 'Отправитель'),
            'receiver_login' => Yii::t('form', 'Получатель'),
        ];
    }
    
    public function isExistSenderLogin($attribute, $param)
    {	
		$data = Partners::find()->where('login = :login', [':login'=>$this->sender_login])->one();
		
		if($data == null)
		{
			$this->addError($attribute, Yii::t('form', 'Такого пользователя нет!'));
		}
		else
		{
			$this->sender_id = $data->id;
		}
	}
	
	public function isExistReceiverLogin($attribute, $param)
    {	
		$data = Partners::find()->where('login = :login', [':login'=>$this->receiver_login])->one();
		
		if($data == null)
		{
			$this->addError($attribute, Yii::t('form', 'Такого пользователя нет!'));
		}
		else
		{
			$this->receiver_id = $data->id;
		}
	}
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function transferBalls()
    {
		$result = false;
		
        if(!$this->validate())
        {	
			return $result;
        }
        
        if($this->sender_id > 0 && $this->receiver_id > 0 && $this->balls > 0)
        {	
			$dbModel = new DbBase();
			$procedureInData = [$this->sender_id, $this->receiver_id, $this->balls, '@p1'];
			$procedureOutData = ['@p1'=>'VAR_OUT_RESULT'];
					
			$procedureResult = $dbModel->callProcedure('transfer_balls', $procedureInData, $procedureOutData);
			$result = $procedureResult['result'];
		}
		
        return $result;
	}
}
