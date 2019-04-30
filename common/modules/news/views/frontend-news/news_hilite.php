<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use common\models\StaticContent;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

if($model !== null)
{
	$this->title = Html::encode($model->meta_title);
	$this->registerMetaTag([
		'name' => 'description',
		'content' => HtmlPurifier::process($model->meta_description),
	]);
	$this->registerMetaTag([
		'name' => 'keywords',
		'content' => HtmlPurifier::process($model->meta_keywords),
	]);
	$this->params['breadcrumbs'][] = $this->title;
}
$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
?>
<h2><?= $this->title; ?></h2>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="card-body">
					<h4 class="card-title"><?= Html::encode($model->title); ?></h4>
					<?= ($model !== null) ? HtmlPurifier::process($model->text) : ''; ?>
				</div>  
			</div>
		</div>
	</div>
</div>
