<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\backoffice\models\Partners;

/**
 * EarningsPaymentsListSearch represents the model behind the search form about `common\modules\structure\models\PaymentsInvoices`.
 */
class ComparePaymentsListSearch extends PaymentsInvoices
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'matrix'], 'integer'],
            [['login', 'email'], 'safe'],
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
		$query = Partners::getTotalPaymentsListByPartners();
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
        
        $query->andFilterWhere([
            'matrix' => $this->matrix
        ]);

        $query->andFilterWhere(['like', 'login', trim($this->login)])
            ->andFilterWhere(['like', 'email', trim($this->email)]);
        
        return $dataProvider;
	}
}
