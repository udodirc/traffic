<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserGroups */

$this->title = Yii::t('form', 'Создать группу');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Группы пользователей'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-groups-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
