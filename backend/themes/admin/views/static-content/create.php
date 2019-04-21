<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StaticContent */

$this->title = Yii::t('form', 'Создать статичный контент');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Статичный контент'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-content-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
