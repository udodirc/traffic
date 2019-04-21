<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\modules\structure\models\Withdrawal;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Вывод денег');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
            [
				'attribute'=>'login',
				'label' => Yii::t('form', 'Имя'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->partnerlogin), 'partner-info?id='.$model->partner_id);
				},
			],
			[
				'attribute' => 'type', 
				'label' => Yii::t('form', 'Тип кошелька'),
				'format'=>'raw',//raw, html
				'content'=>function ($model) use ($paymentsTypes)
				{
					return (isset($paymentsTypes[$model->type])) ? $paymentsTypes[$model->type][0] : '';
				},
				'filter'=>false,
			],
			'amount',
			[
				'attribute' => 'status', 
				'label' => Yii::t('form', 'Статус'),
				'format'=>'raw',//raw, html
				'content'=>function ($model) use ($withdrawalStatuses)
				{
					return (isset($withdrawalStatuses[$model->status])) ? $withdrawalStatuses[$model->status] : '';
				},
				'filter'=>false,
			],
			[
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата запроса'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
			[
				'attribute'=>'reject',
				'label' => Yii::t('form', 'Отклонить'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) 
				{
					if($model->status == Withdrawal::STATUS_SUSPEND)
					{
						return Html::a(Yii::t('form', 'Отклонить'), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-withdrawal/').'reject?id='.$model->id);
					}
					elseif($model->status == Withdrawal::STATUS_REJECT)
					{
						return Yii::t('form', 'Отклонить');
					}
					elseif($model->status == Withdrawal::STATUS_CONFIRM)
					{
						return Yii::t('form', 'Одобрен');
					}
				},
			],
			[
				'attribute'=>'confirm',
				'label' => Yii::t('form', 'Одобрить'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) 
				{
					if($model->status == Withdrawal::STATUS_SUSPEND || $model->status == Withdrawal::STATUS_REJECT)
					{
						return Html::a(Yii::t('form', 'Одобрить'), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-withdrawal/').'confirm?id='.$model->id);
					}
					elseif($model->status == Withdrawal::STATUS_CONFIRM)
					{
						return Yii::t('form', 'Одобрен');
					}
				},
			],
            /*[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{activate}',
				'buttons' => [
					'activate' => function ($url, $model) 
					{
						$icon = ($model->status > 1) ? 'inactive' : 'active';
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
			],*/
            [
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{delete}',
			],
        ],
    ]);
	?>
</div>
