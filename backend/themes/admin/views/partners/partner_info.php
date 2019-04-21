<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = $model->login;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Информация о партнере'), 'url' => ['index']];
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
    <p>
        <?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('form', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('form', 'Вы на самом деле хотите удалить эту запись?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sponsor_name',
            'login',
            'first_name',
            'last_name',
            'email',
            [
				'attribute' => 'created_at', 
				'format' => ['date', 'php:Y-m-d'],
				'filter'=>false,
			],
        ],
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'layout'=>"{pager}\n{summary}\n{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'sponsor_name',
            [
				'attribute'=>'login',
				'label' => Yii::t('form', 'Имя'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->login), 'partner-info?id='.$model->id);
				},
			],
			'email',
            'structure_level',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
	?>
</div>
