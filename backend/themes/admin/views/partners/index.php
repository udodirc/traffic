<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Структура партнеров');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="structure-index">
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
</div>
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
			'first_name',
			'last_name',
			'email',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
?>
