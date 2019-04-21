<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\GoldTokenSettings */

$this->title = Yii::t('form', 'Редактирование: ', [
    'modelClass' => 'GoldTokenSettings',
]) . ' ';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Жетоны - настройки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="gold-token-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
