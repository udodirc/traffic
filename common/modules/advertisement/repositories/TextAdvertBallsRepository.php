<?php

namespace common\modules\advertisement\repositories;

use common\modules\advertisement\models\TextAdvertBalls;

class TextAdvertBallsRepository
{
	public function getTextAdvertByUserID(int $advertID, int $userID) : ?TextAdvertBalls
	{
		return TextAdvertBalls::find()
			->where(['advert_id' => $advertID])
			->andWhere(['user_id' => $userID])
			->one();
	}
}