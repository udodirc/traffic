<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\landings\models\Landings */

$this->title = Yii::t('form', 'Создать лендинг');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Лендинги'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="landings-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'url' => $url,
        'category' => $category,
    ]) ?>
</div>
