<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\MatrixPayments;

/**
 * MatrixPaymentsSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class MatrixPaymentsSearch extends MatrixPayments
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
            [['id', 'structure_number', 'matrix_number', 'matrix_id'], 'integer'],
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
		? MatrixPayments::find()->joinWith(['partner', 'payerPartner'])->where(['matrix_payments.type'=>2])
		: MatrixPayments::find()->joinWith(['partner', 'payerPartner'])->where(['matrix_payments.partner_id'=>$id, 'matrix_payments.structure_number'=>$structure, 'matrix_payments.type'=>2]);

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
            '`partners`.`login`' => trim((string) $this->benefit_login)
        ]);
        
        $query->andFilterWhere([
            '`p2`.`login`' => trim((string) $this->payer_login)
        ]);
        
        $query->andFilterWhere([
            '`matrix_payments`.`id`' => trim((string) $this->id)
        ]);
        
        $query->andFilterWhere([
            '`matrix_payments`.`matrix_number`' => trim((string) $this->matrix_number)
        ]);
        
        $query->andFilterWhere([
            '`matrix_payments`.`matrix_id`' => trim((string) $this->matrix_id)
        ]);
        
        $query->andFilterWhere([
            '`matrix_payments`.`amount`' => trim((string) $this->amount)
        ]);
        
        $query->orderBy('`matrix_payments`.`created_at` DESC');
        
        return $dataProvider;
	}
}
