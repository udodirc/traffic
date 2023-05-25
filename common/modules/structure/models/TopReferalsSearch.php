<?php

namespace common\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\backoffice\models\Partners;

class TopReferalsSearch  extends TopReferals
{
	public $month;
	public $referral_count;
	public $active_partners_count;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['month'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'month' => Yii::t('form', 'Месяц')
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
	public function search($params, $limit = 100)
	{
		$model = new Partners();
		$query = $model->getTopLeaders($this->month);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		if($this->month > 0)
		{
			$query->filterWhere([
				"MONTH(FROM_UNIXTIME(`partners`.`created_at`, '%y-%m-%d'))" => $this->month
			]);
		}

		$query->orderBy('`referrals_count` DESC')
		      ->limit($limit);

		return $dataProvider;
	}
}