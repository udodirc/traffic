<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\Withdrawal */

$this->title = Yii::t('form', 'Create Withdrawal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="withdrawal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
