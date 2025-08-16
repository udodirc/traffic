<?php
use yii\helpers\Html;
use common\components\ContentHelper;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title; ?></h1>
<br/>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $this->title; ?></h4>
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            [
                                'attribute'=>'title',
                                'label' => Yii::t('form', 'Заголовок'),
                                'format'=>'raw',//raw, html
                                'value'=>function ($model) {
                                    return Html::a(Html::encode($model->title), 'news/'.$model->id);
                                },
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => Yii::t('form', 'Дата создания'),
                                'format' => ['date', 'php:Y-m-d H:m:s'],
                                'filter'=>false,
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>