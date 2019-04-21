<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="alert alert-success">
				<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
			</div>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="alert alert-success">
				<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
			</div>
		</div>
	</div>
</div>
<!-- /.flash-error -->
<?php endif; ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Html::encode($this->title); ?></h5>
			</div>
            <div class="ibox-content">
				<?php if (!is_null($model)): ?>
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'id',
							'sponsor_name',
							'login',
							'first_name',
							'last_name',
							'email',
							[
								'attribute' => 'status', 
								'label' => Yii::t('form', 'Статус'),
								'format'=>'raw',//raw, html
								'value'=>(isset($statuses_list[$model->status])) ? $statuses_list[$model->status] : '',
							],
							[
								'attribute' => 'referals_count', 
								'label' => Yii::t('form', 'Кол-во рефералов'),
								'format'=>'raw',//raw, html
								'value'=>$referalsCount,
							],
							[
								'attribute' => 'total_amount', 
								'label' => Yii::t('form', 'Всего заработано - РЕАЛЬНЫЙ ЗАРАБОТОК'),
								'format'=>'raw',//raw, html
								'value'=>$model->total_amount,
							],
							[
								'attribute' => 'demo_total_amount', 
								'label' => Yii::t('form', 'Всего заработано - ОЖИДАЕМЫЙ ЗАРАБОТОК'),
								'format'=>'raw',//raw, html
								'value'=>$model->demo_total_amount.' - ('.Yii::t('form', 'ожидаемый').')',
							],
							[
								'attribute' => 'total_balls', 
								'label' => Yii::t('form', 'Всего заработано - РЕАЛЬНЫЕ БАЛЛЫ'),
								'format'=>'raw',//raw, html
								'value'=>$model->total_balls,
							],
							[
								'attribute' => 'demo_total_balls', 
								'label' => Yii::t('form', 'Всего заработано - ОЖИДАЕМЫЕ БАЛЛЫ'),
								'format'=>'raw',//raw, html
								'value'=>$model->demo_total_balls.' - ('.Yii::t('form', 'ожидаемый').')',
							],
							[
								'attribute' => 'total_balls', 
								'label' => Yii::t('form', 'Общая сумма баллов по бонусу за приглашения'.' - '.Yii::t('form', 'ожидаемый')),
								'format'=>'raw',//raw, html
								'value'=>$demoBallsSum,
							],
							[
								'attribute' => 'total_balls', 
								'label' => Yii::t('form', 'Общая сумма баллов по бонусу за приглашения'),
								'format'=>'raw',//raw, html
								'value'=>$ballsSum,
							],
							[
								'attribute' => 'total_balls', 
								'label' => Yii::t('form', 'Общая сумма баллов по бонусу за закрытие матрицы'.' - '.Yii::t('form', 'ожидаемый')),
								'format'=>'raw',//raw, html
								'value'=>($demoCloseBallsSum > 0) ? $demoCloseBallsSum : 0,
							],
							[
								'attribute' => 'total_balls', 
								'label' => Yii::t('form', 'Общая сумма баллов по бонусу за закрытие матрицы'),
								'format'=>'raw',//raw, html
								'value'=>($closeBallsSum > 0) ? $closeBallsSum : 0,
							],
							[
								'attribute' => 'gold_token', 
								'label' => Yii::t('form', 'Общая сумма по бонусу за жетоны'),
								'format'=>'raw',//raw, html
								'value'=>($goldTokenSum > 0) ? $goldTokenSum : 0,
							],
							[
								'attribute' => 'created_at', 
								'label' => Yii::t('form', 'Дата регистрации'),
								'format' => ['date', 'php:Y-m-d H:m:s'],
								'filter'=>false,
							],
						],
					]) ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Лично приглашенные рефералы'); ?></h5>
			</div>
            <div class="ibox-content">
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
						'login',
						'email',
						[
							'attribute'=>'matrix',
							'label' => Yii::t('form', 'Статус'),
							'format'=>'raw',//raw, html
							'value'=>function ($model) {
								return ($model->matrix > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен');
							},
						],
						[
							'attribute' => 'created_at', 
							'label' => Yii::t('form', 'Дата регистрации'),
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
