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
<h2><?= Html::encode($this->title); ?></h2>
<br/>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
					<?= Html::encode($this->title); ?>
				</div>
			</div>
			<div class="panel-body">
				<?= (isset($content)) ? ContentHelper::checkContentVeiables($content->content) : ''; ?>
			</div>
		</div>
	</div>
</div>
