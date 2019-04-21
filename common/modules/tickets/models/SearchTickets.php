<?php

namespace common\modules\tickets\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\tickets\models\Tickets;
use common\modules\backoffice\models\Partners;

/**
 * SearchTickets represents the model behind the search form about `common\modules\tickets\models\Tickets`.
 */
class SearchTickets extends Tickets
{
	public $date_from;
	public $date_to;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'status', 'created_at'], 'integer'],
            [['subject'], 'safe'],
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
    public function search($id, $params)
    {
        $query = Tickets::find()->where(['partner_id'=>$id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            //'status' => $this->status,
            //'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject]);

        return $dataProvider;
    }
    
    public function searchAll($params)
    {
		$this->load($params);
		
		$model = new Tickets();
		$status = ($this->status != '') ? $this->status : 0;
        $query = $model->getTicketsList($status);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $partner_id = '';
		
		if(isset($params['SearchTickets']['login'])  && $params['SearchTickets']['login'] != '')
		{
			$ticketModel = Partners::find()->select('id')->where('login=:login', [':login' => $params['SearchTickets']['login']])->one();
			$partner_id = ($ticketModel !== null) ? $ticketModel->id : '';
		}
		
		$query->andFilterWhere([
            'tickets.id' => $this->id,
            'partners.id' => $partner_id,
            //'status' => $this->status,
            //'created_at' => $this->created_at,
        ]);
        
        if($this->date_from != '' && $this->date_to != '') 
		{
			$query->andFilterWhere(['between', 'tickets.created_at', strtotime($this->date_from), strtotime($this->date_to)]);
		}
		
		/*if($this->status != '')
        {
			$query->andFilterWhere([
				'tickets.status' => $this->status
			]);
        }*/
		
        $query->andFilterWhere(['like', 'tickets.subject', $this->subject]);

        return $dataProvider;
    }
}
