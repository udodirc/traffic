<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('form', 'Редактирование пользователя');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактирование');
?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'profile' => $profile
    ]) ?>

</div>
