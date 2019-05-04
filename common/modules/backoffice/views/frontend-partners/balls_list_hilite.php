<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<?php if (Yii::$app->session->hasFlash('success')): ?>
			<div class="alert alert-success" role="alert">
				<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
			</div>
			<?php elseif (Yii::$app->session->hasFlash('error')): ?>
			<div class="alert alert-danger" role="alert">
				<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="card-body">
					<h4 class="card-title"><?= $this->title; ?></h4>
					<div class="panel-content">
						<?= GridView::widget([
							'dataProvider' => $dataProvider,
							//'filterModel' => $searchModel,
							'layout'=>"{pager}\n{summary}\n{items}",
							'pager' => [
								'options'=>['class'=>'pagination flex-wrap'],   // set clas name used in ui list of pagination
								'linkOptions' => ['class' => 'page-link'],
								'firstPageLabel'=>Yii::t('form', 'Первый'),   // Set the label for the "first" page button
								'lastPageLabel'=>Yii::t('form', 'Последний'),    // Set the label for the "last" page button
								//'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
								//'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
								'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
							],
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								'id',
								[
									'attribute'=>'partner_id',
									'label'=>Yii::t('form', 'Логин'),
									'format'=>'text', // Возможные варианты: raw, html
									'filterInputOptions'=>['name' => 'BallsSearch[login]'],
									'content'=>function($data){
										return $data->getPartnerName();
									},
								],
								[
									'attribute' => 'structure_number', 
									'label' => Yii::t('form', 'Структура'),
									'format'=>'raw',//raw, html
									'value'=>function ($model) use ($structuresList) {
										return (isset($structuresList[$model->structure_number])) ? $structuresList[$model->structure_number] : '';
									},
								],
								'matrix_number',
								'matrix_id',
								'balls',
								[
									'attribute' => 'created_at', 
									'label' => Yii::t('form', 'Дата оплаты'),
									'format' => ['date', 'php:Y-m-d H:m:s'],
									'filter'=>false,
								],
								//['class' => 'yii\grid\ActionColumn'],
							],
						]); 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
