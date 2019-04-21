<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\components\ContentHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-links">
	<div class="col-sm-12 col-md-12">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
				<?= (isset($content)) ? ContentHelper::checkContentVeiables($content->content) : ''; ?>
			</div>
		</div>
	</div>
</div>
