<?php

namespace common\modules\advertisement\repositories;

use common\models\DbBase;
use common\modules\advertisement\models\TextAdvert;

class TextAdvertRepository
{
	private TextAdvert $model;
	private DbBase $db;

	public function __construct
	(
		TextAdvert $textAdvert,
		DbBase $db
	)
	{
		$this->model = $textAdvert;
		$this->db = $db;
	}

	public function setBalls(
		int $partnerID,
		int $advertUserID,
		int $advertID,
		int $balls,
		int $structure
	): bool
	{
		$procedureResult = $this->db->callProcedure(
			'set_text_advert_balls',
			[$partnerID, $advertUserID, $advertID, $balls, $structure, '@p1'],
			['@p1'=>'VAR_OUT_RESULT']);

		return (isset($procedureResult['result']))
			? $procedureResult['result']
			: false;
	}
}