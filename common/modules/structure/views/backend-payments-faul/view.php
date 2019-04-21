<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\PaymentsFaul */

$this->title = 'ID:'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Payments Fauls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-faul-view">
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
            'login_receiver',
            'login_paid',
            [
				'attribute' => 'payment_type', 
				'label' => Yii::t('form', 'Платежная система'),
				'format'=>'raw',//raw, html
				'value'=>(isset($paymentTypes[$model->payment_type][0])) ? $paymentTypes[$model->payment_type][0] : '',
			],
            'amount',
            'note',
            [
				'attribute' => 'status', 
				'label' => Yii::t('form', 'Статус'),
				'format'=>'raw',//raw, html
				'value'=>($model->status > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен'),
			],
			[
				'attribute' => 'paid', 
				'label' => Yii::t('form', 'Выплата'),
				'format'=>'raw',//raw, html
				'value'=>($model->paid > 0) ? Yii::t('form', 'Выплачен') : Yii::t('form', 'Не выплачен'),
			],
            [
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата ошибки'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
        ],
    ]) ?>
</div>
