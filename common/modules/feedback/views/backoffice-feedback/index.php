<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\web\View;

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
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Информация'); ?></div>
				<div class="panel-options"></div>
			</div>
			<div class="panel-body">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>      
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::encode($this->title); ?></div>
				<div class="panel-options"></div>
			</div>
			<div class="panel-body">
				<span class="right">
					<?= Html::a(Yii::t('form', 'Добавить отзыв'), ['create'], ['class' => 'btn btn-success']) ?>
				</span>
			</div>	
			<!-- panel body -->
			<div class="panel-body">
				<?= ListView::widget([
					'dataProvider' => $feedbackList,
						'options' => [],
					'layout' => "{pager}\n{items}\n",
					'itemView' => function ($model, $key, $index, $widget) {
						return $this->render('partial/_feedback_list_item',['model' => $model]);
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
