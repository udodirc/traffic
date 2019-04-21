<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Permissions */

$this->title = Yii::t('form', 'Создать права');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
