<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\structure\models\GoldTokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Жетоны');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gold-token-index">
	<h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute'=>'partner_id',
				'label'=>Yii::t('form', 'Логин'),
				'format'=>'text', // Возможные варианты: raw, html
				'filterInputOptions'=>['name' => 'GoldTokenSearch[login]'],
				'content'=>function($data){
					return $data->getPartnerName();
				},
			],
			[
				'attribute' => 'structure_number', 
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($structuresList) {
					return (isset($structuresList[$model->structure_number])) ? $structuresList[$model->structure_number] : '';
				},
			],
			'matrix',
			'matrix_id',
			'amount',
			[
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата ативации'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
