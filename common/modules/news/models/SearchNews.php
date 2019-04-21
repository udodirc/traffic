<?php

namespace common\modules\news\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\news\models\News;

/**
 * SearchNews represents the model behind the search form about `common\modules\news\models\News`.
 */
class SearchNews extends News
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'short_text', 'text'], 'safe'],
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
        $query = News::find()->with('author');

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
            'author_id' => $this->author_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'short_text', $this->short_text])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
