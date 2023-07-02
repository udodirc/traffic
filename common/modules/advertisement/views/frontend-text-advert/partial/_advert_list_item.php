<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success" data-collapsed="0">
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= $model->title; ?></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= Html::a($model->text, \Yii::$app->request->BaseUrl.'/partners/text-advert/link/'.$model->id, [
					'title'=>$model->title,
					'target'=>'_blank'
				]); ?>
			</div>
		</div>
	</div>
</div>

