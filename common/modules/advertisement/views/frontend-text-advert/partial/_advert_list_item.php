<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success" data-collapsed="0">
		<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::a($model->title, \Yii::$app->request->BaseUrl.'/text-advert/'.$model->id, []); ?></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= Html::a($model->short_text, \Yii::$app->request->BaseUrl.'/partners/text-advert/'.$model->id, []); ?>		
			</div>	
		</div>
	</div>
</div>
