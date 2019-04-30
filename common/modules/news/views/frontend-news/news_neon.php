<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

if($model !== null)
{
	$this->title = $model->meta_title;
	$this->registerMetaTag([
		'name' => 'description',
		'content' => $model->meta_description,
	]);
	$this->registerMetaTag([
		'name' => 'keywords',
		'content' => $model->meta_keywords,
	]);
	$this->params['breadcrumbs'][] = $this->title;
}
$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::encode($model->title); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= ($model !== null) ? $model->text : '' ?>
			</div>
		</div>
	</div>
</div>
