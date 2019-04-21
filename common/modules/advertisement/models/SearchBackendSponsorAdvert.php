<?php

namespace common\modules\advertisement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\advertisement\models\SponsorAdvert;

/**
 * SearchBackendFrontSponsorAdvert represents the model behind the search form about `common\modules\advertisement\models\SponsorAdvert`.
 */
class SearchBackendSponsorAdvert extends SponsorAdvert
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'created_at'], 'integer'],
            [['name', 'desc'], 'safe'],
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
        $query = SponsorAdvert::find()->select(['id', 'name']);

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
            'partner_id' => $this->partner_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
