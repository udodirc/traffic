<?php

namespace common\modules\advertisement\services;

use common\modules\advertisement\repositories\TextAdvertBallsRepository;

class TextAdvertRequestService
{
	private TextAdvertBallsRepository $textAdvertBallsRepository;

	public function __construct
	(
		TextAdvertBallsRepository $textAdvertBallsRepository
	)
	{
		$this->textAdvertBallsRepository = $textAdvertBallsRepository;
	}

	public function isTextAdvertShowed(int $advertID, int $userID): bool
	{
		return is_null($this->textAdvertBallsRepository->getTextAdvertByUserID( $advertID, $userID));
	}
}