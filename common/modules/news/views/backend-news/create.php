<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\news\models\News */

$this->title = Yii::t('form', 'Создать новость');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Новости'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
