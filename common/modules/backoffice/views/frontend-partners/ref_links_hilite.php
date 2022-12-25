<?php
use yii\helpers\Html;
use common\components\ContentHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= $this->title; ?></h2>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= $this->title; ?></h4>
				<p class="card-description">
					<?= (isset($content)) ? ContentHelper::checkContentVeiables($content->content) : ''; ?>
				</p>
			</div>
		</div>
	</div>
</div>
