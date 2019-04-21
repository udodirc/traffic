<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\GoldToken;
use common\modules\backoffice\models\Partners;

/**
 * GoldTokenSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class GoldTokenSearch extends GoldToken
{
	public $login;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'matrix', 'matrix_id', 'amount'], 'integer'],
            [['structure_number'], 'string'],
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
		$query = GoldToken::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $partner_id = '';
        $structure_number = '';
		
		if(isset($params['GoldTokenSearch']['login'])  && $params['GoldTokenSearch']['login'] != '')
		{
			$partner_id = (Partners::find()->select(['id'])->where(['login'=>trim($params['GoldTokenSearch']['login'])]) != null) ? Partners::find()->select(['id'])->where(['login'=>trim($params['GoldTokenSearch']['login'])])->one()->id : '';
		}
		
		if(isset($params['GoldTokenSearch']['structure_number']) && $params['GoldTokenSearch']['structure_number'] != '')
		{
			if(isset(\Yii::$app->params['structures']))
			{
				$structuresList = array_flip(\Yii::$app->params['structures']);
				$structure_number = (isset($structuresList[trim($params['GoldTokenSearch']['structure_number'])])) ? $structuresList[trim($params['GoldTokenSearch']['structure_number'])] : '';
			}
		}
		
        $query->andFilterWhere([
            'id' => $this->id,
            'partner_id' => $partner_id,
            'structure_number' => $structure_number,
            'matrix' => $this->matrix,
            'matrix_id' => $this->matrix_id,
            'amount' => $this->amount,
        ]);
        
        $query->orderBy('created_at DESC');
        
        return $dataProvider;
	}
}
