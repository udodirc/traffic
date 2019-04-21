<?php
namespace common\modules\messages\models\forms;

use Yii;
use yii\base\Model;
use common\modules\messages\models\Messages;

/**
 * Message form
 */
class MessageForm extends Model
{
	public $type;
	public $login_list;
	public $id_from;
	public $id_to;
	public $subject;
	public $message;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
		return [
			[['type', 'message', 'subject'], 'required', 'message' => Yii::t('form', 'Это поле должно быть заполнено!')],
            [['login_list', 'message'], 'string'],
            [['subject'], 'string', 'max' => 100, 'message' => Yii::t('form', 'В этом поле максимально допустимо 100 символов!')],
            [['id_from', 'id_to'], 'integer'],
            ['type','typeValidator'],
		];
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'base_type' => Yii::t('form', 'База рассылки'),
			'type' => Yii::t('form', 'Тип рассылка'),
			'subject' => Yii::t('form', 'Тема'),
			'login_list' => Yii::t('form', 'Список логинов'),
            'message' => Yii::t('form', 'Сообщение'),
            'id_from' => Yii::t('form', 'Начальное ID'),
            'id_to' => Yii::t('form', 'Последнее ID'),
        ];
    }
    
    //implement the validator
    public function typeValidator($attribute, $params)
    {
		if($this->type == '2' && empty($this->login_list))
        {
            $this->addError('login_list', 'Это поле должно быть заполнено!');
		}
		
		if($this->type == '3' && empty($this->id_from))
        {
            $this->addError('id_from', 'Это поле должно быть заполнено!');
		}
		
		if($this->type == '3' && empty($this->id_to))
        {
            $this->addError('id_to', 'Это поле должно быть заполнено!');
		}
    }
    
    public function createMessage($data)
    {
        if(!$this->validate())
        {
			 return false;
        }
        
        $result = false;
        $model = new Messages();
        $model->title = $data->subject;
        $model->text = $data->message;
        
        if($model->save(false))
        {
			echo $model->id;
		}
		
        return false;
	}
    
    public function sendMessage($data)
    {
        if(!$this->validate())
        {
			 return false;
        }
        
        $result = false;
        $model = new Message();
        
        /*switch($this->type)
        {
			case 1:
			$result = $model->makeMessageByAllPartners($this->message, $this->subject);
			break;
			
			case 2:
			$result = $model->makeMessageByLogins($this->message, $this->login_list, $this->subject);
			break;
			
			case 3:
			$result = $model->makeMessageByIDs($this->id_from, $this->id_to, $this->message, $this->subject);
			break;
		}*/
		
        return $result;
	}
}
