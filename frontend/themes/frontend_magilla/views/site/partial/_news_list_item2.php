<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-success card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-light"><?= Html::a($model->title, \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?></h6>
			</div>
			<div class="pull-right news">
				<?= date("Y-m-d", $model->created_at); ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div  class="panel-wrapper collapse in">
			<div  class="panel-body">
				<?= $model->short_text; ?><?= Html::a(Yii::t('form', 'Подробнее'), \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?>
			</div>
		</div>
	</div>
</div>
