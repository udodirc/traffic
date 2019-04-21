<?php
namespace common\modules\payeer\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\payeer\models\PayeerPayments;
use common\modules\backoffice\models\Partners;

/**
 * PayeerPaymentsSearch represents the model behind the search form about `common\modules\structure\models\InvitePayOff`.
 */
class PayeerPaymentsSearch extends PayeerPayments
{
	public $login;
	public $date_from;
	public $date_to;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'matrix_number', 'places', 'matrix_id', 'type', 'order_id', 'operation_id'], 'integer'],
            [['structure_number', 'login', 'currency'], 'string'],
            [['amount'], 'number'],
            [['date_from', 'date_to'], 'checkDate', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }
    
    public function checkDate($attribute, $param)
    {
		if($this->date_from != '' || $this->date_to != '') 
		{
			if($this->date_from == '' || $this->date_to == '') 
			{
				$this->addError($attribute, Yii::t('form', 'Это поле должно быть заполнено!'));
			}
			else
			{
				$date_from = \DateTime::createFromFormat('d-m-Y', $this->date_from)->format('d-m-Y') == $this->date_from;
				$date_to = \DateTime::createFromFormat('d-m-Y', $this->date_to)->format('d-m-Y') == $this->date_to;
				
				if(!$date_from || !$date_to)
				{
					$this->addError($attribute, Yii::t('form', 'Введен неправильный формат данных!'));
				}
				else
				{
					if(strtotime($this->date_from) > strtotime($this->date_to))
					{
						$this->addError($attribute, Yii::t('form', 'Введена неправильная дата!'));
					}
				}
			}
		}
	}
	
	/**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {	
		$query = PayeerPayments::find()->joinWith(['partner']);
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $structure_number = '';
        $partner_id = '';
        
        if(isset($params['PayeerPaymentsSearch']['login'])  && $params['PayeerPaymentsSearch']['login'] != '')
		{	
			$partner_id = (Partners::find()->select(['id'])->where(['login'=>trim($params['PayeerPaymentsSearch']['login'])]) != null) ? Partners::find()->select(['id'])->where(['login'=>trim($params['PayeerPaymentsSearch']['login'])])->one() : '';
			$partner_id = ($partner_id != '') ? $partner_id->id : '';
		}
        
        $query->andFilterWhere([
            '`partners`.`login`' => trim($this->login)
        ]);
        echo $this->type;
        $query->andFilterWhere([
            '`payeer_payments`.`id`' => trim($this->id),
            '`payeer_payments`.`partner_id`' => $partner_id,
			'`payeer_payments`.`matrix_number`' => trim($this->matrix_number),
            '`payeer_payments`.`matrix_id`' => trim($this->matrix_id),
            '`payeer_payments`.`currency`' => trim($this->currency),
            '`payeer_payments`.`places`' => trim($this->places),
            '`payeer_payments`.`order_id`' => trim($this->order_id),
            '`payeer_payments`.`operation_id`' => trim($this->operation_id),
            '`payeer_payments`.`amount`' => trim($this->amount),
        ]);
        
        if($this->structure_number > 0)
        {
			 $query->andFilterWhere([
				'`payeer_payments`.`structure_number`' => trim($this->structure_number)
			]);
		}
        
        if($this->type > 0)
        {
			 $query->andFilterWhere([
				'`payeer_payments`.`type`' => trim($this->type)
			]);
		}
		
		if($this->date_from != '' && $this->date_to != '') 
		{
			$query->andFilterWhere(['between', 'operation_pay_date', strtotime($this->date_from), strtotime($this->date_to)]);
		}
        
        $query->orderBy('`payeer_payments`.`id` DESC');
        
        return $dataProvider;
	}
}
