<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdminMenu;
use common\models\Menu;

/**
 * AdminMenuSearch represents the model behind the search form about `app\models\AdminMenu`.
 */
class AdminMenuSearch extends AdminMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id'], 'integer'],
            [['name', 'url', 'css'], 'safe'],
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
        $query = AdminMenu::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $parent_id = '';
		
		if(isset($params['AdminMenuSearch']['parent_menu'])  && $params['AdminMenuSearch']['parent_menu'] != '')
		{
			$parent_id = Menu::getMenuDataByName($params['AdminMenuSearch']['parent_menu'], true);
		}
		
        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $parent_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'css', $this->css]);

        return $dataProvider;
    }
}
