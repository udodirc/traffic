<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">
	<div class="action_buttons">
        <?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('form', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('form', 'Вы на самом деле хотите удалить эту запись?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
	<div class="panel-wrapper fixed">
		<div class="panel">
			<div class="title"><h4><?= $this->title; ?></h4></div>
			<div class="content"><?= $model->text; ?></div>
		</div>
	</div>
</div>
