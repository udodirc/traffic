<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pagination;

/**
 * PaginationSearch represents the model behind the search form about `app\models\Pagination`.
 */
class PaginationSearch extends Pagination
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'value'], 'integer'],
            [['menu_id'], 'safe'],
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
        $query = Pagination::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $menu_id = '';
		
		if(isset($params['PaginationSearch']['menu_id'])  && $params['PaginationSearch']['menu_id'] != '')
		{
			$menu = new Menu();
			$menu_id = $menu->getMenuDataByName($params['PaginationSearch']['menu_id'], true);
		}

        $query->andFilterWhere([
            'id' => $this->id,
            'menu_id' => $menu_id,
            'value' => $this->value,
        ]);

        return $dataProvider;
    }
}
