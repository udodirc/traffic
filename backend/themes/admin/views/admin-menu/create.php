<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AdminMenu */

$this->title = Yii::t('form', 'Создать меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Админ меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
