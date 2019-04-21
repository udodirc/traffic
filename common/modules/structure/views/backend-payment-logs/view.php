<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\PaymentsInvoices */

$this->title = 'ID:'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Payments Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-invoices-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'login',
            [
				'attribute' => 'structure_number', 
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>(isset($structuresList[$model->structure_number])) ? $structuresList[$model->structure_number] : '',
			],
            'matrix_number',
            'matrix_id',
            'places',
            [
				'attribute' => 'account_type', 
				'label' => Yii::t('form', 'Тип оплаты'),
				'format'=>'raw',//raw, html
				'value'=>(isset($accountTypesList[$model->account_type])) ? $accountTypesList[$model->account_type] : '',
			],
			'sci_sign',
			'order_id',
            'amount',
            'transact_id',
            'action_type',
            'type',
            [
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата операции'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
        ],
    ]) ?>
</div>
