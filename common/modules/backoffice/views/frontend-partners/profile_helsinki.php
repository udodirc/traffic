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
<div class="alert alert-success fade in">
	<a href="#" class="close" data-dismiss="alert">×</a>
	<span>
		<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
	</span>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger fade in">
	<a href="#" class="close" data-dismiss="alert">×</a>
	<span>
		<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
	</span>
</div><!-- /.flash-success -->
<?php endif; ?>
<div class="col-sm-6 col-md-6">
	<div class="panel">
		<div class="panel-header">
			<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
		</div>
		<div class="panel-header" style="height:60px;">
			<span class="right">
				<?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => (!is_null($model)) ? $model->id : 0], ['class' => 'btn btn-success']) ?>
			</span>
			<span class="right" style="margin-right:10px;">
				<?= Html::a(Yii::t('form', 'Сменить пароль'), ['change-password', 'id' => (!is_null($model)) ? $model->id : 0], ['class' => 'btn btn-success']) ?>
			</span>
		</div>
        <div class="panel-content">
			<div class="table-responsive">
			<?= DetailView::widget(
			[
				'model' => $model,
				'attributes' => [
					'id',
					'login',
					'first_name',
					'last_name',
					'email:email',
					'payeer_wallet',
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
			]) ?>	
			</div>
		</div>
	</div>
</div>
<div class="col-sm-6 col-md-6">
	<div class="panel">
		<div class="panel-header">
			<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
		</div>
        <div class="panel-content">
			<?= (isset($content) && $content != null) ? $content->content : ''; ?>      
		</div>
	</div>
</div>
