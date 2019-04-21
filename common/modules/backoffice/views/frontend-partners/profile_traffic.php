<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

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
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title" style="height:60px;">
				<span class="right">
					<?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => (!is_null($model)) ? $model->id : 0], ['class' => 'btn btn-w-m btn-primary']) ?>
				</span>
				<span class="right" style="margin-right:10px;">
					<?= Html::a(Yii::t('form', 'Сменить пароль'), ['change-password', 'id' => (!is_null($model)) ? $model->id : 0], ['class' => 'btn btn-w-m btn-primary']) ?>
				</span>
			</div>
            <div class="ibox-content">
				<div class="panel-body">
					<?= DetailView::widget(
					[
						'model' => $model,
						'attributes' => [
							'id',
							'login',
							'first_name',
							'last_name',
							'email:email',
							'blockchain',
							[
								'attribute' => 'status', 
								'label' => Yii::t('form', 'Статус'),
								'format'=>'raw',//raw, html
								'value'=>(isset($statuses_list[$model->status])) ? $statuses_list[$model->status] : '',
							],
							[
								'attribute' => 'mailing', 
								'label' => Yii::t('form', 'Рассылка'),
								'format'=>'raw',//raw, html
								'value'=>($model->mailing > 0) ? Yii::t('form', 'Да') : Yii::t('form', 'Нет'),
							],
							[
								'label'  => Yii::t('form', 'Статус'),
								'value'  => ($model->status > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен'),
							],
							[
								'attribute' => 'created_at', 
								'format' => ['date', 'php:Y-m-d H:i:s'],
								'filter'=>false,
							],
						],
					]); 
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Html::encode($this->title); ?></h5>
			</div>
            <div class="ibox-content">
				<div class="panel-body">
					<?= (isset($content) && $content != null) ? $content->content : ''; ?> 
				</div>
			</div>
		</div>
	</div>
</div>
