<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\news\models\SearchNews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Новости');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
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
			<?= Html::a(Yii::t('form', 'Создать новость'), ['create'], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute'=>'author_id',
				'label'=>Yii::t('form', 'Автор'),
				'format'=>'text', // Возможные варианты: raw, html
				'content'=>function($data){
					return $data->getAuthorName();
				},
				'filter' => false
			],
            [
				'attribute'=>'title',
				'label' => Yii::t('form', 'Заголовок'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->title), 'news?id='.$model->id);
				},
			],
            [
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата создания'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
			[
				'attribute' => 'updated_at', 
				'label' => Yii::t('form', 'Дата редактирования'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
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
