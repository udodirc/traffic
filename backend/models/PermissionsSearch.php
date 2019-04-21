<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Permissions;
use common\models\Service;

/**
 * PermissionsSearch represents the model behind the search form about `app\models\Permissions`.
 */
class PermissionsSearch extends Permissions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'controller_id', 'create_perm', 'update_perm', 'delete_perm'], 'integer'],
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
        $query = Permissions::find()->orderBy('group_id ASC, controller_id ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
		
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if(isset($params['PermissionsSearch']['controller']) && $params['PermissionsSearch']['controller'] != '')
		{
			$controllerID = Service::getControllerIDByName(trim($params['PermissionsSearch']['controller']));
			
			$query->andFilterWhere([
				'controller_id' => $controllerID,
			]);
		}
		
		if(isset($params['PermissionsSearch']['group']) && $params['PermissionsSearch']['group'] != '')
		{
			$model = UserGroups::find()->select('id')->where(['name'=>trim($params['PermissionsSearch']['group'])])->one();
			
			$query->andFilterWhere([
				'group_id' => ($model !== NULL) ? $model->id : 0,
			]);
		}

        $query->andFilterWhere([
            'id' => $this->id,
            'create_perm' => $this->create_perm,
            'update_perm' => $this->update_perm,
            'delete_perm' => $this->delete_perm,
        ]);

        return $dataProvider;
    }
}
