<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaginationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Пагинация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagination-index">
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
	<div style="width:100%; overflow:hidden; margin-bottom:10px;">
		<div style="float:right;">
			<?= Html::a(Yii::t('form', 'Создать пагинатор'), ['create'], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"{pager}\n{summary}\n{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
				'attribute'=>'menu_id',
				'label' => Yii::t('form', 'Меню'),
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'PaginationSearch[menu_id]'],
				'content'=>function($model) use ($menu_list) 
				{
					return isset($menu_list[$model->menu_id]) ? $menu_list[$model->menu_id] : '';
                }
			],
            'value',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
