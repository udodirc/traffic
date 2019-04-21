<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Информация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2><?= $this->title; ?></h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<div class="row">
				<?= ($staticContent !== null) ? $staticContent->content : ''?>
			</div>
		</div>
	</div>
</div>
