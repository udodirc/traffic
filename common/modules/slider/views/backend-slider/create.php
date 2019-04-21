<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\slider\models\Slider */

$this->title = Yii::t('form', 'Создать слайдер');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Слайдеры'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'id' => 0,
		'category' => $category,
		'url' => $url,
		'thumbnail' => $thumbnail
    ]) ?>
</div>
