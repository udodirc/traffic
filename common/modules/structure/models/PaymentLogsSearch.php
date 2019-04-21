<?php
namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\PaymentLogs;

/**
 * PaymentLogsSearch represents the model behind the search form about `common\modules\structure\models\PaymentLogs`.
 */
class PaymentLogsSearch extends PaymentsInvoices
{
	public $login;
	
	/**
     * @inheritdoc
    */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'matrix_number', 'matrix_id', 'account_type', 'created_at'], 'integer'],
            [['structure_number', 'login'], 'string'],
            [['amount'], 'number'],
            [['transact_id'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		$model = new PaymentLogs();
		$query = PaymentLogs::find()->joinWith(['partner']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $partner_id = '';
        $structure_number = '';
		
		if(isset($params['PaymentLogsSearch']['login'])  && $params['PaymentLogsSearch']['login'] != '')
		{
			$partner_id = PaymentLogs::getIDByLogin($params['PaymentLogsSearch']['login']);
			$partner_id = ($partner_id !== null) ? $partner_id->partner_id : '';
		}
		
		if(isset($params['PaymentLogsSearch']['structure_number']) && $params['PaymentLogsSearch']['structure_number'] != '')
		{
			if(isset(\Yii::$app->params['structures']))
			{
				$structuresList = array_flip(\Yii::$app->params['structures']);
				$structure_number = (isset($structuresList[trim($params['PaymentLogsSearch']['structure_number'])])) ? $structuresList[trim($params['PaymentLogsSearch']['structure_number'])] : '';
			}
		}

        $query->andFilterWhere([
            'payment_logs.id' => $this->id,
            'payment_logs.partner_id' => $partner_id,
            'payment_logs.structure_number' => $structure_number,
            'payment_logs.matrix_number' => $this->matrix_number,
            'payment_logs.matrix_id' => $this->matrix_id,
            'payment_logs.amount' => $this->amount
        ]);

		if($this->transact_id != '')
		{
			$query->andFilterWhere(['like', 'payment_logs.transact_id', trim($this->transact_id)]);
		}
		
		if($this->date_from != '' && $this->date_to != '') 
		{
			$query->andFilterWhere(['between', 'payment_logs.created_at', strtotime($this->date_from), strtotime($this->date_to)]);
		}

        return $dataProvider;
    }
}
