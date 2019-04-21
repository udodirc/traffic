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
            [
				'attribute' => 'structure_number', 
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>(isset($structuresList[$model->structure_number])) ? $structuresList[$model->structure_number] : '',
			],
            'matrix_number',
            'matrix_id',
            'paid_matrix_id',
            [
				'attribute' => 'login_receiver', 
				'label' => Yii::t('form', 'Получатель'),
				'format'=>'raw',//raw, html,
				'value'=>($model->account_type > 1) ? $model->login_receiver : '',
			],
            'login_paid',
            [
				'attribute' => 'payment_type', 
				'label' => Yii::t('form', 'Платежная система'),
				'format'=>'raw',//raw, html
				'value'=>(isset($paymentTypes[$model->payment_type][0])) ? $paymentTypes[$model->payment_type][0] : '',
			],
            'amount',
            [
				'attribute' => 'account_type', 
				'label' => Yii::t('form', 'Тип оплаты'),
				'format'=>'raw',//raw, html
				'value'=>(isset($accountTypes[$model->account_type])) ? $accountTypes[$model->account_type] : '',
			],
            'transact_id',
            [
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата операции'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
        ],
    ]) ?>
</div>
