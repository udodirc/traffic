<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('menu', 'Добавить рекламу');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
	'model' => $model,
	'partner_id' => $partner_id
]) ?>
