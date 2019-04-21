<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$this->title = Yii::t('form', 'Создать меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
        'status_list' => $status_list,
        'country_list' => $country_list,
    ]) ?>
</div>
