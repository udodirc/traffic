<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\Companies */
$this->title = Yii::t('form', 'Создать запись');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Компании'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'update' => false,
    ]) ?>
</div>
