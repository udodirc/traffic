<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-view">
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
            [
				'label'  => Yii::t('form', 'Родительское меню'),
				'value'  => ($model->menu_name !== '') ? $model->menu_name : Yii::t('form', 'Нет меню'),
			],
            'name',
            'url',
        ],
    ]) ?>
</div>
