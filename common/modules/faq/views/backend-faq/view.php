<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\Faq */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('form', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('form', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('form', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'question',
            'answer:ntext',
        ],
    ]) ?>

</div>
