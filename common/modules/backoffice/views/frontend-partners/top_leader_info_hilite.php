<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\bootstrap\ActiveForm;
use common\components\ContentHelper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Html::encode(Yii::t('menu', 'Топ лидерам'));
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title; ?></h1>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="card-body">
					<h4 class="card-title"><?=$this->title; ?></h4>
					<?= (isset($content)) ? ContentHelper::checkContentVeiables(HtmlPurifier::process($content->content)) : '';?>
				</div>  
			</div>
		</div>
	</div>
</div>
