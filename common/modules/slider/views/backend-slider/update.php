<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\slider\models\Slider */

$this->title = Yii::t('form', 'Редактирование: ', [
    'modelClass' => 'Slider',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Sliders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="slider-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
		'category' => $category,
		'url' => $url,
		'thumbnail' => $thumbnail
    ]) ?>
</div>
