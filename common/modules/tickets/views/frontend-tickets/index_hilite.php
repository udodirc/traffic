<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\tickets\models\SearchTickets */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Запросы');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title); ?></h1>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="card-body">
					<h4 class="card-title"><?= Yii::t('form', 'Информация'); ?></h4>
					<p class="card-description">
						<?= (isset($content) && $content != null) ? $content->content : ''; ?>
					</p>
				</div>  
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
			<?php if (Yii::$app->session->hasFlash('success')): ?>
				<div class="alert alert-success" role="alert">
					<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
				</div>
			<?php elseif (Yii::$app->session->hasFlash('error')): ?>
				<div class="alert alert-danger" role="alert">
					<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
				</div>
			<?php endif; ?>
				<h4 class="card-title"><?= Html::encode($this->title); ?></h4>
				<p class="card-description">
					<span class="right">
						<?= Html::a(Yii::t('form', 'Создать'), \Yii::$app->request->BaseUrl.'/tickets/create', ['class' => 'btn btn-primary mr-2']) ?>
					</span>
				</p>
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
