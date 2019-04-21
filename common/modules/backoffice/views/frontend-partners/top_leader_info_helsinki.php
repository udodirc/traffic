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
<div class="top-leaders">
	<div class="col-sm-12 col-md-12">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
				<?= (isset($content)) ? ContentHelper::checkContentVeiables($content->content) : '';?>     
			</div>
		</div>
	</div>
</div>
