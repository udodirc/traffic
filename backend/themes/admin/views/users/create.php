<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('form', 'Создать пользователя');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'profile' => 1
    ]) ?>

</div>
