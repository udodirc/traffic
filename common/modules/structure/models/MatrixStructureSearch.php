<?php
namespace common\modules\structure\models;

use common\modules\structure\models\Matrix;
use common\modules\backoffice\models\Partners;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Search matrix structure
 */
class MatrixStructureSearch extends Matrix
{
	public $id;
    public $open_date;
    public $close_date;
    public $partner_login;
    public $sponsor_login;
    public $real_sponsor_login;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'open_date', 'close_date'], 'integer'],
            [['partner_login', 'sponsor_login', 'real_sponsor_login'], 'string'],
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
    public function search($params, $structure, $number, $demo = false)
    {
		$demo = ($demo) ? 'demo_' : '';
		
		$query = (new \yii\db\Query())
		->select('`main_matrix`.`id`, `main_matrix`.`matrix_id` AS `sponsor_matrix_id`, `main_matrix`.`partner_id` AS `partner_id`, `main_matrix`.`open_date`, 
		`main_matrix`.`close_date`, `partners`.`login` AS `partner_login`, `sponsor_partners`.`login` AS `sponsor_login`, `referal_sponsor`.`login` AS `referal_sponsor_login`')
		->from('`'.$demo.'matrix_'.$structure.'_'.$number.'`')
		->leftJoin('`'.$demo.'matrix_'.$structure.'_'.$number.'` `main_matrix`', '`'.$demo.'matrix_'.$structure.'_'.$number.'`.`id` = `main_matrix`.`matrix_id`')
		->leftJoin('`partners`', '`partners`.`id` = `main_matrix`.`partner_id`')
		->leftJoin('`partners` `sponsor_partners`', '`sponsor_partners`.`id` = `main_matrix`.`parent_id`')
		->leftJoin('`partners` `referal_sponsor`', '`referal_sponsor`.`id` = `partners`.`sponsor_id`')
		->where('`main_matrix`.`id` IS NOT NULL AND `main_matrix`.`matrix_id` IS NOT NULL AND `main_matrix`.`partner_id` IS NOT NULL AND `main_matrix`.`open_date` IS NOT NULL 
		AND `main_matrix`.`close_date` IS NOT NULL AND `partners`.`login` IS NOT NULL AND `sponsor_partners`.`login` IS NOT NULL')
		->orderBy('`main_matrix`.`id` DESC');
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
             return $dataProvider;
        }
        
        if($this->id != '')
		{	
			$query->andFilterWhere([
				'`'.$demo.'main_matrix_'.$number.'`.`id`' => $this->id,
			]);
		}
        
        if($this->partner_login != '')
		{	
			$query->andFilterWhere([
				'`partners`.`login`' => $this->partner_login,
			]);
		}
        
        return $dataProvider;
	}
}
