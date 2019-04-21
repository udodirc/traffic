<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\DemoMatrixPayments;

/**
 * DemoMatrixPaymentsSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class DemoMatrixPaymentsSearch extends DemoMatrixPayments
{
	public $benefit_login;
	public $payer_login;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['benefit_login', 'payer_login'], 'string'],
            [['id', 'matrix_number', 'matrix_id'], 'integer'],
            [['amount'], 'number']
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
    public function search($params, $structure, $id)
    {
		$query = ($structure <= 0 || $id <= 0) 
		? DemoMatrixPayments::find()->joinWith(['partner', 'payerPartner'])->where(['demo_matrix_payments.type'=>2])
		: DemoMatrixPayments::find()->joinWith(['partner', 'payerPartner'])->where(['demo_matrix_payments.partner_id'=>$id, 'demo_matrix_payments.structure_number'=>$structure, 'demo_matrix_payments.type'=>2]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            '`partners`.`login`' => trim($this->benefit_login)
        ]);
        
        $query->andFilterWhere([
            '`p2`.`login`' => trim($this->payer_login)
        ]);
        
        $query->andFilterWhere([
            '`demo_matrix_payments`.`id`' => trim($this->id)
        ]);
        
        $query->andFilterWhere([
            '`demo_matrix_payments`.`matrix_number`' => trim($this->matrix_number)
        ]);
        
        $query->andFilterWhere([
            '`demo_matrix_payments`.`matrix_id`' => trim($this->matrix_id)
        ]);
        
        $query->andFilterWhere([
            '`demo_matrix_payments`.`amount`' => trim($this->amount)
        ]);
        
        $query->orderBy('`created_at` DESC');
        
        return $dataProvider;
	}
}
