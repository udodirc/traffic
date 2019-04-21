<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\structure\models\GoldTokenSettings */

$this->title = Yii::t('form', 'Создать');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Жетоны - настройки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gold-token-settings-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
