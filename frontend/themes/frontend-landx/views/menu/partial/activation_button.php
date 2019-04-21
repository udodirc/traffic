<?php
use yii\helpers\Html;
use common\models\Service;
use common\modules\backoffice\models\Partners;

$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;

if($id > 0)
{
	$partnerData = Partners::find()->where(['id'=>$id])->one();

	if($partnerData != null)
	{
		if (Service::isActionAllowed('is_activation_allowed'))
		{ 
			if($partnerData->matrix_1 > 0)
			{
				echo Html::a(Yii::t('form', 'Оплатить'), ['backoffice/frontend-partners/reserve-places', 'id' => $id, 'payment_type' => 2, 'structure' => 2], ['class' => 'btn btn-wide btn-success']);
			}
			else
			{
				echo Html::a(Yii::t('form', 'Оплатить'), ['backoffice/frontend-partners/pay', 'id' => $id, 'payment_type' => 2, 'structure' => 1, 'matrix' => 1, 'places' => 1], ['class' => 'btn btn-wide btn-success']); 
			} 
		}
	}
}
