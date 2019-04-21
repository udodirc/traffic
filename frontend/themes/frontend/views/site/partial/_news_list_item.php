<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success" data-collapsed="0">
		<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::a($model->title, \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?></div>
				<div class="panel-options">
					<span class="news_date"><?= date("Y-m-d", $model->created_at); ?></span>
				</div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= $model->short_text; ?><?= Html::a(Yii::t('form', 'Подробнее'), \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?>		
			</div>	
		</div>
	</div>
</div>
