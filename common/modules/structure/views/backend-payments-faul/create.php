<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\PaymentsFaul */

$this->title = Yii::t('form', 'Create Payments Faul');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Payments Fauls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-faul-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
