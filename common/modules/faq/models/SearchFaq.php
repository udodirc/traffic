<?php

namespace common\modules\faq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\faq\models\Faq;

/**
 * SearchFaq represents the model behind the search form about `common\modules\faq\models\Faq`.
 */
class SearchFaq extends Faq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['type'], 'string'],
            [['question', 'answer'], 'safe'],
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
        $query = Faq::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $type = '';
        
        if(isset($params['SearchFaq']['type'])  && $params['SearchFaq']['type'] != '')
		{
			$typesList = (isset(Yii::$app->params['faq_type'])) ? Yii::$app->params['faq_type'] : [];
			$param = trim($params['SearchFaq']['type']);
			
			if(!empty($typesList))
			{
				$typesList = array_flip($typesList);
				
				if(isset($typesList[$param]))
				{
					 $type = $typesList[$param];
				}
			}
			
		}

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $type,
        ]);

        $query->andFilterWhere(['like', 'question', trim((string)$this->question)]);

        return $dataProvider;
    }
}
