<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card">
	<img class="card-img-top" src="https://via.placeholder.com/363x363" alt="Card image cap">
	<div class="card-body">
		<h4 class="card-title mt-3"><?= Html::a($model->title, \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?></h4>
		<p class="card-text"><?= $model->short_text; ?></p>
	</div>
</div>
