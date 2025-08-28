<?php
namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\AutoPayOffLogs;
use common\modules\backoffice\models\Partners;

/**
 * AutoPayOffLogsSearch represents the model behind the search form about `common\modules\structure\models\AutoPayOffLogs`.
 */
class AutoPayOffLogsSearch extends AutoPayOffLogs
{
	public $login;
	public $date_from;
	public $date_to;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'matrix_number', 'matrix_id', 'type', 'paid_off', 'created_at'], 'integer'],
            [['structure_number', 'login'], 'string'],
            [['amount'], 'number'],
            [['date_from', 'date_to'], 'checkDate', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }
    
    public function checkDate($attribute, $param)
    {
		if($this->date_from != '' || $this->date_to != '') 
		{
			if($this->date_from == '' || $this->date_to == '') 
			{
				$this->addError($attribute, Yii::t('form', 'Это поле должно быть заполнено!'));
			}
			else
			{
				$date_from = \DateTime::createFromFormat('d-m-Y', $this->date_from)->format('d-m-Y') == $this->date_from;
				$date_to = \DateTime::createFromFormat('d-m-Y', $this->date_to)->format('d-m-Y') == $this->date_to;
				
				if(!$date_from || !$date_to)
				{
					$this->addError($attribute, Yii::t('form', 'Введен неправильный формат данных!'));
				}
				else
				{
					if(strtotime($this->date_from) > strtotime($this->date_to))
					{
						$this->addError($attribute, Yii::t('form', 'Введена неправильная дата!'));
					}
				}
			}
		}
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
		$query = AutoPayOffLogs::find()->joinWith(['partner']);
		
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
        
        if(isset($params['AutoPayOffLogsSearch']['login'])  && $params['AutoPayOffLogsSearch']['login'] != '')
		{	
			$partner_id = (Partners::find()->select(['id'])->where(['login'=>trim($params['AutoPayOffLogsSearch']['login'])]) != null) ? Partners::find()->select(['id'])->where(['login'=>trim($params['AutoPayOffLogsSearch']['login'])])->one() : '';
			$partner_id = ($partner_id != '') ? $partner_id->id : '';
		}
        
        $query->andFilterWhere([
            '`partners`.`login`' => trim((string)$this->login)
        ]);
        echo $this->type;
        $query->andFilterWhere([
            '`auto_pay_off_logs`.`id`' => $this->id,
            '`auto_pay_off_logs`.`partner_id`' => $partner_id,
			'`auto_pay_off_logs`.`matrix_number`' => trim((string)$this->matrix_number),
            '`auto_pay_off_logs`.`matrix_id`' => trim((string)$this->matrix_id),
            '`auto_pay_off_logs`.`paid_off`' => trim((string)$this->paid_off),
            '`auto_pay_off_logs`.`amount`' => trim((string)$this->amount),
        ]);
        
        if($this->structure_number > 0)
        {
			 $query->andFilterWhere([
				'`auto_pay_off_logs`.`structure_number`' => trim($this->structure_number)
			]);
		}
        
        if($this->type > 0)
        {
			 $query->andFilterWhere([
				'`auto_pay_off_logs`.`type`' => trim($this->type)
			]);
		}
		
		if($this->date_from != '' && $this->date_to != '') 
		{
			$query->andFilterWhere(['between', '`auto_pay_off_logs`.`created_at`', strtotime($this->date_from), strtotime($this->date_to)]);
		}
        
        $query->orderBy('`auto_pay_off_logs`.`created_at` DESC');
        
        return $dataProvider;
	}
}
