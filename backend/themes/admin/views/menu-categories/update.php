<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MenuCategories */

$this->title = Yii::t('form', 'Редактировать: ', [
    'modelClass' => 'Тип меню',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Тип меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактировать');
?>
<div class="menu-categories-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
