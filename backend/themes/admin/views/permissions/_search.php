<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PermissionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permissions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'group_id') ?>

    <?= $form->field($model, 'controller') ?>

    <?= $form->field($model, 'create_perm') ?>

    <?= $form->field($model, 'update_perm') ?>

    <?php // echo $form->field($model, 'delete_perm') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('form', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
