<?php
use yii\helpers\Html;
use common\models\Service;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger">
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<div class="row">
	<!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <div class="col-md-6">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content activation">
				<?php if (Service::isActionAllowed('is_activation_allowed')): ?>
					<?php if ($model->matrix_1 > 0): ?>
					<div class="activation_btn">
						<?= Html::a(Yii::t('form', 'Оплатить короткие матрицы'), ['reserve-places', 'id' => $id, 'payment_type' => 2, 'structure' => 1], ['class' => 'btn btn-wide btn-success']) ?>
					</div>
					<?php else: ?>
					<div class="activation_btn">
						<?= Html::a(Yii::t('form', 'Оплатить'), ['pay', 'id' => $id, 'payment_type' => 2, 'structure' => 1, 'matrix' => 1, 'places' => 1], ['class' => 'btn btn-wide btn-success']) ?>
					</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="panel-content">
				<?= (isset($tile_content) && $tile_content != null) ? $tile_content->content : ''; ?>     
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>      
			</div>
		</div>
	</div>
</div>
