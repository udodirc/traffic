<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\modules\structure\models\Withdrawal;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Выплаты партнеров');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$attribute = [];

if($compare)
{
	$attribute = [
		'class' => 'yii\grid\ActionColumn',
		'template' => '{view}',
		'buttons' => [
			'view' => function ($url,$model) 
			{
				return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \Yii::$app->request->BaseUrl.'/accounting/backend-accounting/payments-by-id?id='.$model->id, ['target'=>'blank']);
			},
		]
	];
}
?>
<div class="partner-info-view">
	<h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="notice error">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<div class="actions_wrapper">
		<div class="action_wrapper">
			<?= Html::a(Yii::t('form', 'Сравнить выплаты'), 
				['compare-payments'], 
				[
					'class' => 'btn btn-success',
				]
			);
			?>
		</div>
	</div>
	<div class="actions_wrapper">
		<?= Yii::t('form', 'Общее количество партнеров').' - '.$dataProvider->getTotalCount(); ?>
	</div>
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
            [
				'attribute'=>'login',
				'label' => Yii::t('form', 'Логин'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model['login']), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partner-info?id='.$model['id'], ['target'=>'blank']);
				},
			],
			'email',
			[
				'attribute' => 'structure_number', 
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($structuresList) {
					return (isset($structuresList[$model['structure_number']])) ? $structuresList[$model['structure_number']] : '';
				},
			],
			'matrix_'.$stuctureNumber,
			[
				'attribute'=>'total_payment_sum',
				'label' => Yii::t('form', 'Реальная выплата'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return $model['total_payment_sum'].' - '.Html::a(Yii::t('form', 'подробнее'), 'earnings-payment-list?id='.$model['id']);
				},
			],
			[
				'attribute'=>'total_amount',
				'label' => Yii::t('form', 'Выплаты по матрицам'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($stuctureNumber) {
					return $model['total_amount_'.$stuctureNumber].' - '.Html::a(Yii::t('form', 'подробнее'), 'earnings-list?id='.$model['id']);
				},
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}',
				'buttons' => [
					'view' => function ($url,$model) 
					{
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \Yii::$app->request->BaseUrl.'/accounting/backend-accounting/compare-payment-by-id?id='.$model['id'], ['target'=>'blank']);
					},
				]
			]
        ],
    ]);
	?>
</div>
