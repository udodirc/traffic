<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\PaymentsInvoices */

$this->title = Yii::t('form', 'Create Payments Invoices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Payments Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-invoices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
