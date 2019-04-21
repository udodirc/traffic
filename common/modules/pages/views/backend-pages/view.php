<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\pages\models\Pages */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-view">
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
            'name',
            'url',
        ],
    ]) ?>

</div>
