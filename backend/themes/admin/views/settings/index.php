<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Админ меню');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-menu-index">
	<h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
				'attribute'=>'parent_id',
				'label' => Yii::t('form', 'Родительское меню'),
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'AdminMenuSearch[parent_menu]'],
				'content'=>function($model) use ($menu_list) 
				{
					return isset($menu_list[$model->parent_id]) ? $menu_list[$model->parent_id] : '';
                }
			],
            'name',
            'url',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
