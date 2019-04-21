<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Index';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
	<?= ($staticContent !== null) ? $staticContent->content : ''?>
</div>
