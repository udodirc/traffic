<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\AdminMenu;

/* @var $this yii\web\View */
/* @var $model app\models\Pagination */

$this->title = Yii::t('form', 'Просмотр');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Пагинация'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagination-view">
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
    <?php
		echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
				'label'  => Yii::t('form', 'Меню'),
				'value'  => ($model->menu_name !== NULL) ? $model->menu_name : Yii::t('form', 'Меню нет'),
			],
            'value',
        ],
    ]) ?>
</div>
