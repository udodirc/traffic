<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UsersSearch represents the model behind the search form about `app\models\User`.
 */
class UsersSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'group_id', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
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
        $query = User::find()->where('id != 1');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		$group = (isset($params['UsersSearch']['group_name'])  && $params['UsersSearch']['group_name'] != '') ? intval(UserGroups::find()->where('name = :name', [':name' => trim($params['UsersSearch']['group_name'])])->one()->id) : $this->group_id;
		
		$query->andFilterWhere([
            'id' => $this->id,
            'group_id' => $group,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        if(isset($params['UsersSearch']['status_name']) && $params['UsersSearch']['status_name'] != '')
        {
			$statusList = array_flip(User::getStatusList());
			$query->andFilterWhere(['status' => isset($statusList[trim($params['UsersSearch']['status_name'])]) ? $statusList[trim($params['UsersSearch']['status_name'])] : 0]);
		}
		
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);
		
        return $dataProvider;
    }
}
