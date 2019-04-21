<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\feedback\models\SearchFeedback */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Спонсорская реклама');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">
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
			<?= Html::a(Yii::t('form', 'Создать'), ['create'], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
