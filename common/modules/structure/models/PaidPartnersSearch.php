<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\backoffice\models\Partners;

/**
 * PaidPartnersSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class PaidPartnersSearch extends Partners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
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
		$model = new Partners();
		$query = $model->getPaidPartnersList();

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
            'partners.id' => trim((int)$this->id)
        ]);
        
        $query->andFilterWhere(['like', 'partners.login', trim((string)$this->login)])
            ->andFilterWhere(['like', 'partners.email', trim((string)$this->email)]);
        
        return $dataProvider;
	}
}
