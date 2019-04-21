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
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="alert alert-success">
				<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
			</div>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="alert alert-danger">
				<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
			</div>
		</div>
	</div>
</div>
<!-- /.flash-error -->
<?php endif; ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Информация'); ?></h5>
			</div>
            <div class="ibox-content">
				<div class="panel-body">
					<?= (isset($content) && $content != null) ? $content->content : ''; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title" style="height:65px;">
				<div style="float:right;">
					<?= Html::a(Yii::t('form', 'Добавить отзыв'), ['create'], ['class' => 'btn btn-w-m btn-primary']) ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="wrapper wrapper-content">
	<div class="row animated fadeInRight">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?= $this->title; ?></h5>
                    <div class="ibox-content inspinia-timeline">
						<!--TIMELINE left-->
						<div class="timeline">
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
		</div>
	</div>
</div>
