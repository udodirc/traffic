<?php
namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\InvitePayOff;
use common\modules\backoffice\models\Partners;

/**
 * InvitePayOffSearch represents the model behind the search form about `common\modules\structure\models\InvitePayOff`.
 */
class InvitePayOffSearch extends InvitePayOff
{
	public $benefit_login;
	public $payer_login;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'matrix_number', 'matrix_id', 'amount'], 'integer'],
            [['structure_number', 'benefit_login', 'payer_login'], 'string'],
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
		? InvitePayOff::find()->joinWith(['partner', 'benefitPartner'])
		: InvitePayOff::find()->joinWith(['partner', 'benefitPartner'])->where(['`invite_pay_off`.`benefit_partner_id`'=>$id, '`invite_pay_off`.`structure_number`'=>$structure]);

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
		
		/*if(isset($params['InvitePayOffSearch']['login'])  && $params['InvitePayOffSearch']['login'] != '')
		{	
			$partner_id = (Partners::find()->select(['id'])->where(['login'=>trim($params['InvitePayOffSearch']['login'])]) != null) ? Partners::find()->select(['id'])->where(['login'=>trim($params['InvitePayOffSearch']['login'])])->one() : '';
			$partner_id = ($partner_id != '') ? $partner_id->id : '';
		}
		
		if(isset($params['InvitePayOffSearch']['structure_number']) && $params['InvitePayOffSearch']['structure_number'] != '')
		{
			if(isset(\Yii::$app->params['structures']))
			{
				$structuresList = array_flip(\Yii::$app->params['structures']);
				$structure_number = (isset($structuresList[trim($params['InvitePayOffSearch']['structure_number'])])) ? $structuresList[trim($params['InvitePayOffSearch']['structure_number'])] : '';
			}
		}*/
		
		$query->andFilterWhere([
            '`partners`.`login`' => trim($this->payer_login)
        ]);
        
        $query->andFilterWhere([
            '`p2`.`login`' => trim($this->benefit_login)
        ]);
		
        $query->andFilterWhere([
            '`invite_pay_off`.`id`' => $this->id,
            '`invite_pay_off`.`partner_id`' => $partner_id,
            '`invite_pay_off`.`structure_number`' => $structure_number,
            '`invite_pay_off`.`matrix_number`' => $this->matrix_number,
            '`invite_pay_off`.`matrix_id`' => $this->matrix_id,
            '`invite_pay_off`.`amount`' => $this->amount,
        ]);
        
        $query->orderBy('`invite_pay_off`.`created_at` DESC');
        
        return $dataProvider;
	}
}
