<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Service;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PermissionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Права доступа');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-index">
    <h1><?= Html::encode($this->title) ?></h1>
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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<div style="width:100%; overflow:hidden; margin-bottom:10px;">
		<div style="float:right;">
			<?= Html::a(Yii::t('form', 'Создать права'), ['create'], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute'=>'group_id',
				'label' => Yii::t('form', 'Группа'),
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'PermissionsSearch[group]'],
				'content'=>function($model) use ($group_list) 
				{
					return isset($group_list[$model->group_id]) ? $group_list[$model->group_id] : '';
                }
			],
            [
				'attribute'=>'controller_id',
				'label' => Yii::t('form', 'Контроллер'),
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'PermissionsSearch[controller]'],
				'content'=>function($model)
				{
					return Service::getControllerNameByID($model->controller_id);
                }
			],
			[
				'label'=>Yii::t('form', 'Просмотр'),
				'format'=>'raw',
				'value' => function($model) use ($permisions_list)
				{
					if(isset($permisions_list[$model->group_id][$model->controller_id]['view_perm']))
					{
						return ($permisions_list[$model->group_id][$model->controller_id]['view_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет');
					}
				}
			],
            [
				'label'=>Yii::t('form', 'Создание'),
				'format'=>'raw',
				'value' => function($model) use ($permisions_list)
				{
					if(isset($permisions_list[$model->group_id][$model->controller_id]['create_perm']))
					{
						return ($permisions_list[$model->group_id][$model->controller_id]['create_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет');
					}
				}
			],
			[
				'label'=>Yii::t('form', 'Редактирование'),
				'format'=>'raw',
				'value' => function($model) use ($permisions_list)
				{
					if(isset($permisions_list[$model->group_id][$model->controller_id]['update_perm']))
					{
						return ($permisions_list[$model->group_id][$model->controller_id]['update_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет');
					}
				}
			],
			[
				'label'=>Yii::t('form', 'Удаление'),
				'format'=>'raw',
				'value' => function($model) use ($permisions_list)
				{
					if(isset($permisions_list[$model->group_id][$model->controller_id]['delete_perm']))
					{
						return ($permisions_list[$model->group_id][$model->controller_id]['delete_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет');
					}
				}
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
