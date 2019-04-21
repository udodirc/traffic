<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\tickets\models\Tickets */

$this->title = Yii::t('form', 'Update {modelClass}: ', [
    'modelClass' => 'Tickets',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Update');
?>
<div class="tickets-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
