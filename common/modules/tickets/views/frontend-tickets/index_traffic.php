<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */

/* @var $searchModel common\modules\tickets\models\SearchTickets */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger">
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::encode($this->title); ?></div>
				<div class="panel-options"></div>
			</div>
			<div class="panel-body">
				<span class="right">
					<?= Html::a(Yii::t('form', 'Создать'), \Yii::$app->request->BaseUrl.'/tickets/create', ['class' => 'btn btn-w-m btn-primary']) ?>
				</span>
			</div>	
			<!-- panel body -->
			<div class="panel-body">
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
