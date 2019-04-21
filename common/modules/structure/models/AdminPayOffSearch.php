<?php
namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\AdminPayOff;
use common\modules\backoffice\models\Partners;

/**
 * AdminPayOffSearch represents the model behind the search form about `common\modules\structure\models\AdminPayOff`.
 */
class AdminPayOffSearch extends AdminPayOff
{
	public $login;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'matrix_number', 'matrix_id'], 'integer'],
            [['amount'], 'number'],
            [['structure_number', 'login'], 'string'],
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
    public function search($params, $structure = 0, $id = 0)
    {
		$query = ($structure <= 0 || $id <= 0) 
		? AdminPayOff::find()->joinWith(['partner'])
		: AdminPayOff::find()->joinWith(['partner'])->where(['`admin_pay_off`.`partner_id`'=>$id, '`admin_pay_off`.`structure_number`'=>$structure]);

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
		
		if(isset($params['AdminPayOffSearch']['login']) && $params['AdminPayOffSearch']['login'] != '')
		{	
			$query->andFilterWhere([
				'`partners`.`login`' => trim($params['AdminPayOffSearch']['login'])
			]);
		}
		
		if(isset($params['AdminPayOffSearch']['structure_number']) && $params['AdminPayOffSearch']['structure_number'] != '')
		{	
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			
			if(!empty($structuresList))
			{
				$structuresList = array_flip($structuresList);
				
				if(isset($structuresList[$params['AdminPayOffSearch']['structure_number']]))
				{
					$structure_number = $structuresList[$params['AdminPayOffSearch']['structure_number']];
				}
			}
		}
		
        $query->andFilterWhere([
            '`admin_pay_off`.`id`' => $this->id,
            '`admin_pay_off`.`partner_id`' => $partner_id,
            '`admin_pay_off`.`structure_number`' => $structure_number,
            '`admin_pay_off`.`matrix_number`' => $this->matrix_number,
            '`admin_pay_off`.`matrix_id`' => $this->matrix_id,
            '`admin_pay_off`.`amount`' => $this->amount,
        ]);
        
        $query->orderBy('`admin_pay_off`.`created_at`');
        
        return $dataProvider;
	}
}
