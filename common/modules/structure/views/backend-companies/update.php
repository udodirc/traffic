<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\Companies */

$this->title = Yii::t('form', 'Редактировать: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Компании'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактировать');
?>
<div class="companies-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'update' => true,
    ]) ?>
</div>
