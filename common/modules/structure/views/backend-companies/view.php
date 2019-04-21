<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\Companies */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Компании'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('form', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('form', 'Вы на самом деле хотите удалить запись?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'matrix',
            'name',
            'amount',
        ],
    ]) ?>
</div>
