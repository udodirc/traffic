<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\PaymentsFaul;

/**
 * PaymentsFaulSearch represents the model behind the search form about `common\modules\structure\models\PaymentsFaul`.
 */
class PaymentsFaulSearch extends PaymentsFaul
{
	public $login_receiver;
	public $login_paid;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'matrix_number', 'matrix_id', 'paid_matrix_id', 'partner_id', 'paid'], 'integer'],
            [['amount'], 'number'],
            [['structure_number', 'login_receiver', 'login_paid'], 'string']
        ];
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
		$this->load($params);
		
		$model = new PaymentsFaul();
        $query = $model->getFaulsList($this->paid);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $partner_id = '';
        $paid_matrix_partner_id = '';
        $structure_number = '';
		
		if(isset($params['PaymentsFaulSearch']['login_receiver']) && $params['PaymentsFaulSearch']['login_receiver'] != '')
		{
			$partner_id = PaymentsFaul::getIDByLoginReceiver($params['PaymentsFaulSearch']['login_receiver']);
			$partner_id = ($partner_id !== null) ? $partner_id->partner_id : '';
		}
		
		if(isset($params['PaymentsFaulSearch']['login_paid']) && $params['PaymentsFaulSearch']['login_paid'] != '')
		{
			$paid_matrix_partner_id = PaymentsFaul::getIDByLoginPaid($params['PaymentsFaulSearch']['login_paid']);
			$paid_matrix_partner_id = ($paid_matrix_partner_id !== null) ? $paid_matrix_partner_id->paid_matrix_partner_id : '';
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
            'payments_faul.id' => $this->id,
            'payments_faul.matrix_number' => $this->matrix_number,
            'payments_faul.structure_number' => $structure_number,
            'payments_faul.matrix_id' => $this->matrix_id,
            'payments_faul.paid_matrix_id' => $this->paid_matrix_id,
            'partner_id' => $partner_id,
            'paid_matrix_partner_id' => $paid_matrix_partner_id,
            'payments_faul.amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
