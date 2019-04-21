<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\Faq */

$this->title = Yii::t('form', 'Редактировать', [
    'modelClass' => 'Faq',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Update');
?>
<div class="faq-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'update' => true,
    ]) ?>

</div>
