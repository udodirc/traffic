<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\structure\models\CompaniesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Компании');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('form', 'Создать запись'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'matrix',
            'name',
            'amount',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
