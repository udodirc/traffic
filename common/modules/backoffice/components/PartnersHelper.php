<?php
namespace common\modules\backoffice\components;

use yii;

class PartnersHelper
{
	public static function getStatusContent()
    {
		return [
			0=>'inactive-status-content', 
			1=>'active-status-content'
		];
	}
}
