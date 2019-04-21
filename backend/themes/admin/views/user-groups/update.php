<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserGroups */

$this->title = Yii::t('form', 'Редактировать: ', [
    'modelClass' => 'Группы пользователей',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Группы пользователей'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактировать');
?>
<div class="user-groups-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
