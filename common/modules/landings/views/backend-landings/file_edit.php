<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\landings\models\Landings */

$this->title = Yii::t('form', 'Редактировать:');
$model->file = $text;
?>
<div class="file-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'file')->textarea(['rows' => 6]) ?>
	<div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Обновить'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
