<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StaticContent */

$this->title = Yii::t('form', Yii::t('form', 'Редактировать').':') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Статичный контент'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактировать');
?>
<div class="static-content-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
