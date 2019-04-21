<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Структура партнеров');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="structure-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= Yii::t('form', 'Матрица').' '.$matrix ?><span style="float:right;"><?= Html::a(Yii::t('form', (!$demo) ? 'Демо структура' : 'Реальная структура'), ['matrix-structure?matrix='.$matrix.'&demo='.((!$demo) ? 1 : 0)], ['class' => 'btn btn-success']) ?></span></h2>
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
            'id',
            [
				'attribute' => 'partner_login', 
				'label' => Yii::t('form', 'Логин партнера'),
				'filter'=>true,
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($demo) {
					return Html::a(Html::encode($model['partner_login']), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : Url::base().'/backoffice/backend-partners/').'structure?id='.$model['partner_id']);
				},
			],
			[
				'attribute' => 'sponsor_matrix_id', 
				'label' => Yii::t('form', 'ID матрицы спонсора'),
				'filter'=>false,
			],
            [
				'attribute' => 'sponsor_login', 
				'label' => Yii::t('form', 'Спонсор матрицы'),
				'filter'=>false,
			],
            [
				'attribute' => 'referal_sponsor_login', 
				'label' => Yii::t('form', 'Спонсор реферала'),
				'filter'=>false,
			],
            [
				'attribute' => 'open_date', 
				'label' => Yii::t('form', 'Дата открытия матрицы'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
			[
				'attribute' => 'close_date', 
				'label' => Yii::t('form', 'Дата закрытия матрицы'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
        ],
    ]);
?>
