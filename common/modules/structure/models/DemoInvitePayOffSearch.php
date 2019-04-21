<?php
namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\DemoInvitePayOff;
use common\modules\backoffice\models\Partners;

/**
 * DemoInvitePayOffSearch represents the model behind the search form about `common\modules\structure\models\DemoInvitePayOff`.
 */
class DemoInvitePayOffSearch extends InvitePayOff
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
    public function search($params, $structure, $id)
    {
		$query = ($structure <= 0 || $id <= 0) 
		? DemoInvitePayOff::find()->joinWith(['partner', 'benefitPartner'])
		: DemoInvitePayOff::find()->joinWith(['partner', 'benefitPartner'])->where(['`demo_invite_pay_off`.`benefit_partner_id`'=>$id, '`demo_invite_pay_off`.`structure_number`'=>$structure]);

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
            '`demo_invite_pay_off`.`id`' => $this->id,
            '`demo_invite_pay_off`.`partner_id`' => $partner_id,
            '`demo_invite_pay_off`.`structure_number`' => $structure_number,
            '`demo_invite_pay_off`.`matrix_number`' => $this->matrix_number,
            '`demo_invite_pay_off`.`matrix_id`' => $this->matrix_id,
            '`demo_invite_pay_off`.`amount`' => $this->amount,
        ]);
        
        $query->orderBy('`demo_invite_pay_off`.`created_at` DESC');
        
        return $dataProvider;
	}
}
