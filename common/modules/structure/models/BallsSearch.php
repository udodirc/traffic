<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\backoffice\models\Partners;
use common\modules\structure\models\Balls;

/**
 * BallsSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class BallsSearch extends Balls
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
            [['id', 'partner_id', 'matrix_number', 'matrix_id', 'balls'], 'integer'],
            [['structure_number', 'login'], 'string'],
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
		$model = new Balls();
		$query = Balls::find()->joinWith(['partner']);

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
        
        if(isset($params['BallsSearch']['login']) && $params['BallsSearch']['login'] != '')
		{	
			$query->andFilterWhere([
				'`partners`.`login`' => trim($params['BallsSearch']['login'])
			]);
		}
		
		if(isset($params['BallsSearch']['structure_number']) && $params['BallsSearch']['structure_number'] != '')
		{	
			$structuresList = (isset(\Yii::$app->params['structures'])) ? \Yii::$app->params['structures'] : [];
			
			if(!empty($structuresList))
			{
				$structuresList = array_flip($structuresList);
				
				if(isset($structuresList[$params['BallsSearch']['structure_number']]))
				{
					$structure_number = $structuresList[$params['BallsSearch']['structure_number']];
				}
			}
		}
		
        $query->andFilterWhere([
            '`balls`.`id`' => $this->id,
            '`balls`.`partner_id`' => $partner_id,
            '`balls`.`structure_number`' => $structure_number,
            '`balls`.`matrix_number`' => $this->matrix_number,
            '`balls`.`matrix_id`' => $this->matrix_id,
            '`balls`.`balls`' => $this->balls,
        ]);
        
        if($this->date_from != '' && $this->date_to != '') 
		{
			$query->andFilterWhere(['between', '`balls`.`created_at`', strtotime($this->date_from), strtotime($this->date_to)]);
		}
        
        $query->orderBy('`balls`.`created_at`');
        
        return $dataProvider;
	}
}
