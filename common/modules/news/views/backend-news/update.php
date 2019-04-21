<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\news\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Новости'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактировать');
?>
<div class="news-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
