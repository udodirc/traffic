<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MenuCategories */

$this->title = Yii::t('form', 'Создать тип');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Тип меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
