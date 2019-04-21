<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Contacts */

$this->title = Yii::t('form', 'Create Contacts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Contacts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'field_list' => $field_list,
    ]) ?>

</div>
