<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use vova07\imperavi\Widget as Redactor;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('menu', 'Редактировать рекламу');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
	'model' => $model,
	'partner_id' => $partner_id
]) ?>
