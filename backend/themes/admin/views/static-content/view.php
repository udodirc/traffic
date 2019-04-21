<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StaticContent */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Статичный контент'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-content-view">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
    <h1><?= Html::encode($this->title) ?></h1>
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
            'content:ntext',
            [
				'label'  => Yii::t('form', 'Статус'),
				'value'  => ($model->status > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен'),
			],
        ],
    ]) ?>
</div>
