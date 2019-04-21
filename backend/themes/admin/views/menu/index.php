<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Меню');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">
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
			<?= Html::a(Yii::t('form', 'Создать меню'), ['create'], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"{pager}\n{summary}\n{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute'=>'category_id',
				'label' => Yii::t('form', 'Тип меню'),
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'MenuSearch[menu_type]'],
				'content'=>function($model) use ($categories_list) 
				{
					return isset($categories_list[$model->category_id]) ? $categories_list[$model->category_id] : '';
                }
			],
            [
				'attribute'=>'parent_id',
				'label' => Yii::t('form', 'Родительское меню'),
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'MenuSearch[parent_menu]'],
				'content'=>function($model) use ($menu_list) 
				{
					return isset($menu_list[$model->parent_id]) ? $menu_list[$model->parent_id] : '';
                }
			],
			[
				'attribute'=>'controller_id',
				'label' => Yii::t('form', 'Тип меню'),
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'MenuSearch[controller]'],
				'content'=>function($model) use ($controler_list) 
				{
					return isset($controler_list[$model->controller_id]) ? $controler_list[$model->controller_id] : '';
                }
			],
            'name',
            'url',
            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{activate}',
				'buttons' => [
					'activate' => function ($url, $model) 
					{
						$icon = ($model->status > 0) ? 'inactive' : 'active';
						return Html::a('<span class="glyphicon glyphicon-status-'.$icon.'"></span>', $url, [
							'title' => Yii::t('form', 'Активация'),
						]);
					}
				],
				'urlCreator' => function ($action, $model, $key, $index) 
				{
					if ($action === 'activate') 
					{
						$baseUrl = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
						$url = ($baseUrl == 'index') ? '' : Yii::$app->controller->id.DIRECTORY_SEPARATOR;
						$url.= 'status?id='.$model->id.'&status='.$model->status;
						return $url;
					}
				}
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
