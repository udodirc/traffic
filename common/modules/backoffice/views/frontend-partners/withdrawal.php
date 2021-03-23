<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

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
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title">
					<?= Yii::t('form', 'Список выплат за личные приглашения'); ?>
				</div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= GridView::widget([
					'dataProvider' => $invitePayOffList,
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
							'attribute'=>'payer_login',
							'label' => Yii::t('form', 'Логин плательщика'),
							'value' => 'partner.login',
						],
						[
							'attribute'=>'benefit_login',
							'label' => Yii::t('form', 'Логин получателя'),
							'value' => 'benefitPartner.login',
						],
						'matrix_number',
						'matrix_id',
						'amount',
						[
							'attribute'=>'witdrawal',
							'label' => Yii::t('form', 'Запрос на вывод'),
							'format'=>'raw',//raw, html
							'value'=>function ($model) 
							{
								return Html::a(Yii::t('form', 'Запрос на вывод'), 'witdrawal-request?id='.$model->id);
							},
						],
						[
							'attribute' => 'created_at', 
							'label' => Yii::t('form', 'Дата'),
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
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title">
					<?= Yii::t('form', 'Список выплат за матрицы'); ?>
				</div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= GridView::widget([
					'dataProvider' => $matrixPaymentsList,
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
							'attribute'=>'benefit_login',
							'label' => Yii::t('form', 'Логин получателя'),
							'value' => 'partner.login',
						],
						[
							'attribute'=>'payer_login',
							'label' => Yii::t('form', 'Логин плательщика'),
							'value' => 'payerPartner.login',
						],
						'matrix_number',
						'matrix_id',
						'amount',
						[
							'attribute' => 'created_at', 
							'label' => Yii::t('form', 'Дата'),
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
