<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\pages\models\Pages */

$this->title = Yii::t('form', 'Создать страницу');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Страница'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
