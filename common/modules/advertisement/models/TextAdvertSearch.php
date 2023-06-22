<?php

namespace common\modules\advertisement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\advertisement\models\TextAdvert;

/**
 * TextAdvertSearch represents the model behind the search form about `common\modules\advertisement\models\TextAdvert`.
 */
class TextAdvertSearch extends TextAdvert
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
            [['id', 'partner_id', 'status', 'balls'], 'integer'],
            [['title', 'link'], 'string'],
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
    public function search($id, $params)
    {
        $query = TextAdvert::find()->with('partner')->where('`status` > 0 AND `partner_id` = :id', [':id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
//        $query->andFilterWhere([
//            '`partners`.`login`' => trim($this->login)
//        ]);
//
//        $query->andFilterWhere([
//            '`text_advert`.`id`' => $this->id,
//            '`text_advert`.`balls`' => $this->balls,
//            '`text_advert`.`status`' => $this->status
//        ]);
//
//        $query->andFilterWhere(['like', '`text_advert`.`title`', $this->title])
//            ->andFilterWhere(['like', '`text_advert`.`link`', $this->link]);
//
//        if($this->date_from != '' && $this->date_to != '')
//		{
//			$query->andFilterWhere(['between', '`text_advert`.`created_at`', strtotime($this->date_from), strtotime($this->date_to)]);
//		}
        
        $query->orderBy('`text_advert`.`created_at` DESC');

        return $dataProvider;
    }
}
