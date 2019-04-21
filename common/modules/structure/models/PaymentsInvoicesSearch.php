<?php
namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\PaymentsInvoices;

/**
 * PaymentsInvoicesSearch represents the model behind the search form about `common\modules\structure\models\PaymentsInvoices`.
 */
class PaymentsInvoicesSearch extends PaymentsInvoices
{
	public $login_receiver;
	public $login_paid;
	
	/**
     * @inheritdoc
    */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'matrix_number', 'matrix_id', 'paid_matrix_partner_id', 'paid_matrix_id', 'payment_type', 'created_at'], 'integer'],
            [['structure_number', 'login_receiver', 'login_paid', 'account_type', 'wallet'], 'string'],
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
		$model = new PaymentsInvoices();
        $query = $model->getInvoicesList();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $account_type = '';
		$partner_id = '';
        $paid_matrix_partner_id = '';
        $structure_number = '';
		
		if(isset($params['PaymentsInvoicesSearch']['login_receiver'])  && $params['PaymentsInvoicesSearch']['login_receiver'] != '')
		{
			$partner_id = PaymentsInvoices::getIDByLoginReceiver($params['PaymentsInvoicesSearch']['login_receiver']);
			$partner_id = ($partner_id !== null) ? $partner_id->partner_id : '';
		}
		
		if(isset($params['PaymentsInvoicesSearch']['login_paid'])  && $params['PaymentsInvoicesSearch']['login_paid'] != '')
		{
			$paid_matrix_partner_id = PaymentsInvoices::getIDByLoginPaid($params['PaymentsInvoicesSearch']['login_paid']);
			$paid_matrix_partner_id = ($paid_matrix_partner_id !== null) ? $paid_matrix_partner_id->paid_matrix_partner_id : '';
		}
		
		if(isset($params['PaymentsInvoicesSearch']['account_type'])  && $params['PaymentsInvoicesSearch']['account_type'] != '')
		{
			$accountTypes = array_flip(Yii::$app->params['account_types']);
			$account_type = (isset($accountTypes[$params['PaymentsInvoicesSearch']['account_type']])) ? $accountTypes[$params['PaymentsInvoicesSearch']['account_type']] : '';
		}
		
		if(isset($params['PaymentsFaulSearch']['structure_number']) && $params['PaymentsFaulSearch']['structure_number'] != '')
		{
			if(isset(\Yii::$app->params['structures']))
			{
				$structuresList = array_flip(\Yii::$app->params['structures']);
				$structure_number = (isset($structuresList[trim($params['PaymentsFaulSearch']['structure_number'])])) ? $structuresList[trim($params['PaymentsFaulSearch']['structure_number'])] : '';
			}
		}

        $query->andFilterWhere([
            'payments_invoices.id' => $this->id,
            'partner_id' => $partner_id,
            'payments_faul.structure_number' => $structure_number,
            'matrix_number' => $this->matrix_number,
            'matrix_id' => $this->matrix_id,
            'paid_matrix_partner_id' => $paid_matrix_partner_id,
            'paid_matrix_id' => $this->paid_matrix_id,
            'payment_type' => $this->payment_type,
            'account_type' => $account_type,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ]);

		if($this->transact_id != '')
		{
			$query->andFilterWhere(['like', 'transact_id', trim($this->transact_id)]);
		}
		
		if($this->date_from != '' && $this->date_to != '') 
		{
			$query->andFilterWhere(['between', 'payments_invoices.created_at', strtotime($this->date_from), strtotime($this->date_to)]);
		}

		if($this->wallet != '')
		{
			$partnerList = PaymentsInvoices::getIDByWallet(trim($this->wallet));
			
			if(!empty($partnerList))
			{
				$partnerList = array_column($partnerList, 'id');
				$query->andFilterWhere(['in', 'partner_id', $partnerList]);
			}
		}

        return $dataProvider;
    }
}
