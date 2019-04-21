<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\tickets\models\SearchTickets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tickets-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'partner_id') ?>

    <?= $form->field($model, 'subject') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('form', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
