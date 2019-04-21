<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Топ лидеров');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-info-view">
	<h1><?= Html::encode($this->title) ?></h1>
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
				'attribute'=>'login',
				'label' => Yii::t('form', 'Имя'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model['login']), 'partner-info?id='.$model['id']);
				},
			],
			'email',
			[
				'attribute'=>'referals_count',
				'label' => Yii::t('form', 'Кол-во рефералов'),
				'format'=>'raw',//raw, html
			],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
	?>
</div>
