<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contacts */

$this->title = Yii::t('form', 'Update {modelClass}: ', [
    'modelClass' => 'Contacts',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Contacts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Update');
?>
<div class="contacts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
