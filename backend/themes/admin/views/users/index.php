<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Users;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">
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
	<div style="width:100%; overflow:hidden; margin-bottom:10px;">
		<div style="float:right;">
			<?= Html::a(Yii::t('form', 'Создать пользователя'), ['create'], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<div style="width:100%; overflow:hidden, margin-bottom:20px;">
	<?= GridView::widget([
	'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout'=>"{pager}\n{summary}\n{items}",
    'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'id',
            'username',
            'email:email',
            [
				'attribute'=>'status',
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'UsersSearch[status_name]'],
				'content'=>function($model)
				{
					return ($model->status > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен');
                }
			],
            [
				'attribute'=>'group',
				'format'=>'text',//raw, html
				'filterInputOptions'=>['name' => 'UsersSearch[group_name]'],
				'content'=>function($model) use ($group_list)
				{
					return isset($group_list[$model->group_id]) ? $group_list[$model->group_id] : '';
                }
			],
            [
				'attribute' => 'created_at', 
				'format' => ['date', 'php:Y-m-d H:i:s'],
				'filter'=>false,
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{status}',
				'buttons' => [
					'status' => function ($url, $model) 
					{
						$icon = ($model->status > 0) ? 'inactive' : 'active';
						
						return Html::a('<span class="glyphicon glyphicon-status-'.$icon.'"></span>', $url, [
							'title' => Yii::t('form', 'Статус'),
						]);
					}
				],
				'urlCreator' => function ($action, $model, $key, $index) 
				{
					if ($action === 'status') 
					{
						$baseUrl = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
						$url = ($baseUrl == 'index') ? '' : Yii::$app->controller->id.DIRECTORY_SEPARATOR;
						$url.= 'status?id='.$model->id.'&status='.$model->status;
						return $url;
					}
				}
			],
			[
				'class' => \yii\grid\ActionColumn::className(),
				'buttons'=>[
					'update'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['users/update','id'=>$model['id'],'profile'=>0]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                                                ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
					}
				],
				'template'=>'{view}{update}{delete}',
			]
		],
	]); 
	?>
	</div>
</div>
