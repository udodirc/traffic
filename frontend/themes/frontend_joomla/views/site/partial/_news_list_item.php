<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="col-lg-12">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="pull-left bold">
				<h6 class="panel-title txt-light"><?= Html::a($model->title, \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?></h6>
			</div>
			<div class="pull-right news">
				<?= date("Y-m-d", $model->created_at); ?>
			</div>
			<div class="clearfix"></div>        
		</div>
        <div class="panel-body">
			<?= $model->short_text; ?><?= Html::a(Yii::t('form', 'Подробнее'), \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?>
		</div>
	</div>
</div>
