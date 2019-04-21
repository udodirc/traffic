<?php
use yii\helpers\Html;
use yii\web\View;
use common\models\StaticContent;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="col-md-12">
	<div class="panel panel-default card-view">
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<div class="alert alert-success alert-dismissable">
					<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="zmdi zmdi-check pr-15 pull-left"></i>
					<p class="pull-left"><?= Html::encode(Yii::$app->session->getFlash('success')); ?></p>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="col-md-12">
	<div class="panel panel-default card-view">
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<div class="alert alert-info alert-dismissable">
					<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="zmdi zmdi-info-outline pr-15 pull-left"></i>
					<p class="pull-left"><?= Html::encode(Yii::$app->session->getFlash('error')); ?></p>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.flash-error -->
<?php endif; ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark"><?= Html::encode($this->title); ?></h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div  class="panel-body">
					<?= ListView::widget([
						'dataProvider' => $newsList,
						'options' => [],
						'layout' => "{pager}\n{items}\n",
						'itemView' => function ($model, $key, $index, $widget) {
							return $this->render('partial/_news_list_item',['model' => $model]);
						},
						'pager' => [
							'maxButtonCount' => 10,
						],
					]);
					?>  
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark"><?= Html::encode($this->title); ?></h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div  class="panel-wrapper collapse in">
				<div  class="panel-body">
					<?= ($staticContent !== null) ? $staticContent->content : ''; ?> 
				</div>
			</div>
		</div>
	</div>
</div>
