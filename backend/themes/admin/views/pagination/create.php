<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pagination */

$this->title = Yii::t('form', 'Создать пагинатор');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Paginations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagination-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
