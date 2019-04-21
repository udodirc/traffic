<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\MatrixPayments;

/**
 * EarningsListSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class EarningsListSearch extends MatrixPayments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'matrix_number', 'matrix_id'], 'integer'],
            [['amount'], 'number'],
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
    public function search($params, $id)
    {
		$query = MatrixPayments::find()->select(['id', 'structure_number', 'matrix_number', 'matrix_id', 'amount', 'created_at'])->where('partner_id =:partner_id AND type = 2', [':partner_id'=>$id]);

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
            'id' => trim($this->id)
        ]);
        
        if(isset($params['EarningsListSearch']['matrix_number']) && $params['EarningsListSearch']['matrix_number'] > 0)
        {
			$query->andFilterWhere([
				'matrix_number' => $this->matrix_number
			]);
        }
        
        $query->andFilterWhere([
            'matrix_id' => $this->matrix_id
        ]);
        
        $query->andFilterWhere([
            'amount' => $this->amount
        ]);
        
        return $dataProvider;
	}
}
