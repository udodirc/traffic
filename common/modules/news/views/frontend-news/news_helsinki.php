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
<div class="news">
	<div class="ref-links">
		<div class="col-sm-12 col-md-12">
			<div class="panel">
				<div class="panel-header">
					<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
				</div>
				<div class="panel-content">
					<?= ($model !== null) ? $model->text : '' ?>
				</div>
			</div>
		</div>
	</div>
</div>
