<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\tickets\models\SearchTickets */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Запросы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="alert alert-success fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="alert alert-danger fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<div class="col-sm-12 col-md-12">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode(Yii::t('form', 'Информация')); ?></h4>
			</div>
			<div class="panel-content">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>      
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-12">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
				<span class="right">
					<?= Html::a(Yii::t('form', 'Создать'), \Yii::$app->request->BaseUrl.'/tickets/create', ['class' => 'btn btn-success']) ?>
				</span>
			</div>
			<div class="panel-content">
				<div class="table-responsive">
					<?= GridView::widget([
						'dataProvider' => $dataProvider,
						//'filterModel' => $searchModel,
						'layout'=>"{pager}\n{summary}\n{items}",
						'pager' => [
							//'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
							'firstPageLabel'=>Yii::t('form', 'Первый'),   // Set the label for the "first" page button
							'lastPageLabel'=>Yii::t('form', 'Последний'),    // Set the label for the "last" page button
							'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
							'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
							'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
						],
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							'id',
							[
								'attribute' => 'subject', 
								'label' => Yii::t('form', 'Тема'),
								'format'=>'raw',//raw, html
								'value'=>function ($model) {
									return Html::a(Html::encode($model->subject), 'ticket/'.$model->id);
								},
							],
							[
								'attribute'=>'status',
								'label' => Yii::t('form', 'Статус'),
								'format'=>'raw',//raw, html
								'value'=>function ($model) {
									return ($model->status > 0) ? Yii::t('form', 'Ожидает вашего ответа') : Yii::t('form', 'Ожидайте ответа');
								},
							],
							[
								'attribute' => 'created_at', 
								'label' => Yii::t('form', 'Создан'),
								'format' => ['date', 'php:Y-m-d H:m:s'],
								'filter'=>false,
							],
						],
					]);
					?> 
				</div>
			</div>
		</div>
	</div>
</div>
