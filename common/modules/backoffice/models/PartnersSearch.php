<?php

namespace common\modules\backoffice\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\backoffice\models\Partners;

/**
 * PartnersSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class PartnersSearch extends Partners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['country', 'wallet'], 'string'],
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
		$model = new Partners;
        $query = $model->getPartnersList(true);

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
            'partners_2.id' => trim($this->id)
        ]);
        
        $query->andFilterWhere([
            'partners_2.iso' => $this->country
        ]);

        $query->andFilterWhere(['like', 'partners_2.login', trim($this->login)])
            ->andFilterWhere(['like', 'partners_2.email', trim($this->email)]);
           
        if($this->wallet != '')
		{
			$partnerList = Partners::getIDByWallet($this->wallet);
			
			if(!empty($partnerList))
			{
				$partnerList = array_column($partnerList, 'id');
				$query->andFilterWhere(['in', 'partners_2.id', $partnerList]);
			}
		}

        return $dataProvider;
    }
}
