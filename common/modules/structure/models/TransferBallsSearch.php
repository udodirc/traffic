<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\structure\models\TransferBalls;
use common\modules\backoffice\models\Partners;

/**
 * TransferBallsSearch represents the model behind the search form about `common\modules\backoffice\models\Partners`.
 */
class TransferBallsSearch extends TransferBalls
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id'], 'integer'],
            [['sender_login', 'receiver_login'], 'string'],
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
		$model = new Balls();
		$query = TransferBalls::find()->select('`sender`.`login` AS `sender_login`, `partners`.`login` AS `receiver_login`, `transfer_balls`.`id`, `transfer_balls`.`balls`, `transfer_balls`.`created_at`')->joinWith([
			'receiver', 
			'sender'=> function ($q) {
				$q->from(Partners::tableName() . ' sender');
			}
		]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'transfer_balls.id' => trim($this->id)
        ]);
        
        if(isset($params['TransferBallsSearch']['receiver_login'])  && $params['TransferBallsSearch']['receiver_login'] != '')
		{
			$senderID = Partners::find()->select('id')->where(['login'=>trim($params['TransferBallsSearch']['receiver_login'])])->one();
			$senderID = ($senderID != null) ? $senderID->id : 0;
			
			if($senderID > 0)
			{
				$query->andFilterWhere([
					'receiver_id' => $senderID
				]);
			}
		}
		
		if(isset($params['TransferBallsSearch']['sender_login'])  && $params['TransferBallsSearch']['sender_login'] != '')
		{
			$senderID = Partners::find()->select('id')->where(['login'=>trim($params['TransferBallsSearch']['sender_login'])])->one();
			$senderID = ($senderID != null) ? $senderID->id : 0;
			
			if($senderID > 0)
			{
				$query->andFilterWhere([
					'sender_id' => $senderID
				]);
			}
		}
        
        return $dataProvider;
	}
}
