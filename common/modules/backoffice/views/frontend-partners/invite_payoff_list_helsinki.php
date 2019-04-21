<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invite-payoff-list">
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
			<div class="panel-content">
				<div class="table-responsive">
					<?= GridView::widget([
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
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
</div>
