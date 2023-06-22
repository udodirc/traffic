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
			<div class="panel-body media">
                <i class="mdi mdi-cellphone-link icon-sm align-self-center mr-3">
	                <?= Html::a('Редактировать', \Yii::$app->request->BaseUrl.'/partners/text-advert/edit/'.$model->id, []); ?>
                </i>
				<?= Html::a($model->text, \Yii::$app->request->BaseUrl.'/partners/text-advert/'.$model->id, []); ?>
			</div>	
		</div>
	</div>
</div>
