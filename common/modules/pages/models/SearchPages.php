<?php

namespace common\modules\pages\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\pages\models\Pages;

/**
 * SearchPages represents the model behind the search form about `common\modules\pages\models\Pages`.
 */
class SearchPages extends Pages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['body', 'name', 'url'], 'safe'],
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
        $query = Pages::find();

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
        ]);

        $query->andFilterWhere(['=', 'name', $this->body]);
        $query->andFilterWhere(['=', 'url', $this->body]);
        $query->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
