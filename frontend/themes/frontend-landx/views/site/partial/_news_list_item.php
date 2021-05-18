<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
<div class="card">
	<div class="card-body">
		<h4 class="card-title mt-3"><?= Html::a(Html::encode($model->title), \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?></h4>
		<p class="card-text"><?= HtmlPurifier::process($model->short_text); ?></p>
	</div>
</div>
