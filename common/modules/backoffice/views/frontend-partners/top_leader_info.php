<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\ContentHelper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Топ лидерам');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title); ?></h1>
<br/>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::encode($this->title); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= (isset($content)) ? ContentHelper::checkContentVeiables($content->content) : '';?>
			</div>
		</div>
	</div>
</div>
