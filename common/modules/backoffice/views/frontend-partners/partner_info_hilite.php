<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\components\ContentHelper;

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
		<div style="height:50px; overflow:hidden; margin-top:20px;">
			<?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => (!is_null($model)) ? $model['id'] : 0], ['class' => 'btn btn-success']) ?>
			<?= Html::a(Yii::t('form', 'Сменить пароль'), ['change-password', 'id' => (!is_null($model)) ? $model['id'] : 0], ['class' => 'btn btn-success']) ?>
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
			<!-- panel body -->
			<div class="panel-body">
			<?php if (!is_null($model)): ?>
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => array_merge([
						'id',
						[
								'attribute' => 'sponsor_name', 
								'label' => Yii::t('form', 'Имя споснсора'),
								'format'=>'raw',//raw, html
								'value'=>$model['sponsor_name'],
							],
							[
								'attribute' => 'login', 
								'label' => Yii::t('form', 'Логин'),
								'format'=>'raw',//raw, html
								'value'=>$model['login'],
							],
							[
								'attribute' => 'first_name', 
								'label' => Yii::t('form', 'Имя'),
								'format'=>'raw',//raw, html
								'value'=>$model['first_name'],
							],
							[
								'attribute' => 'last_name', 
								'label' => Yii::t('form', 'Фамилия'),
								'format'=>'raw',//raw, html
								'value'=>$model['last_name'],
							],
							'email',
							[
								'attribute' => 'payeer_wallet', 
								'label' => Yii::t('form', 'Payeer кошелек'),
								'format'=>'raw',//raw, html
								'value'=>$model['payeer_wallet'],
							],
							[
								'attribute' => 'status', 
								'label' => Yii::t('form', 'Статус'),
								'format'=>'raw',//raw, html
								'value'=>(isset($statuses_list[$model['status']])) ? $statuses_list[$model['status']] : '',
							],
							[
								'attribute' => 'referals_count', 
								'label' => Yii::t('form', 'Кол-во рефералов'),
								'format'=>'raw',//raw, html
								'value'=>$referalsCount,
							],
							[
								'attribute' => 'total_balls', 
								'label' => Yii::t('form', 'Всего заработано баллов - РЕАЛЬНЫЙ ЗАРАБОТОК'),
								'format'=>'raw',//raw, html
								'value'=>$model['total_balls_1'],
							],
							[
								'attribute' => 'demo_total_balls', 
								'label' => Yii::t('form', 'Всего заработано баллов - ОЖИДАЕМЫЙ ЗАРАБОТОК'),
								'format'=>'raw',//raw, html
								'value'=>$model['demo_total_balls_1'],
							],
							[
								'attribute' => 'total_amount', 
								'label' => Yii::t('form', 'Всего заработано - РЕАЛЬНЫЙ ЗАРАБОТОК'),
								'format'=>'raw',//raw, html
								'value'=>$model['total_amount_1'],
							],
							[
								'attribute' => 'demo_total_amount', 
								'label' => Yii::t('form', 'Всего заработано - ОЖИДАЕМЫЙ ЗАРАБОТОК'),
								'format'=>'raw',//raw, html
								'value'=>$model['demo_total_amount_1'].' - ('.Yii::t('form', 'демо').')',
							],
							[
								'attribute' => 'created_at', 
								'label' => Yii::t('form', 'Дата регистрации'),
								'format' => ['date', 'php:Y-m-d H:m:s'],
								'filter'=>false,
							],
						], $partnerEarningsInfo)
					]) ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Информация'); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= (isset($content)) ? ContentHelper::checkContentVeiables($content->content) : ''; ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title">
					<?= Yii::t('form', 'Лично приглашенные рефералы'); ?>
				</div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					//'filterModel' => $searchModel,
					'layout'=>"{pager}\n{summary}\n{items}",
					'rowOptions' => function ($model, $index, $widget, $grid)
					{
						if($model['matrix_1'] > 0)
						{
							return ['class' => 'tr_active_status'];
						}
						else
						{
							return [];
						}
					},
					'pager' => [
						//'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
						'firstPageLabel'=>Yii::t('form', 'Первый'),   // Set the label for the "first" page button
						'lastPageLabel'=>Yii::t('form', 'Последний'),    // Set the label for the "last" page button
						'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
						'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
						'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
					],
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						'id',
						'login',
						'email',
						'ref_count',
						[
							'attribute' => 'geo', 
							'label' => Yii::t('form', 'Страна'),
							'format'=>'raw',//raw, html
							'content'=>function ($model) use ($isoList)
							{
								$country = (isset($isoList[$model['iso']])) ? $isoList[$model['iso']] : '';
								
								return ($model['iso'] != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($model['iso']).'.png'), ['alt'=>$country, 'title'=>$country]) : '';
							},
							'filter'=>false,
						],
						[
							'attribute'=>'matrix',
							'label' => Yii::t('form', 'Статус'),
							'format'=>'raw',//raw, html
							'value'=>function ($model) {
								return ($model['matrix_1'] > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен');
							},
						],
						'matrix_1',
						[
							'attribute' => 'created_at', 
							'label' => Yii::t('form', 'Дата регистрации'),
							'format' => ['date', 'php:Y-m-d H:m:s'],
							'filter'=>false,
						],
						[
							'attribute' => 'open_date', 
							'label' => Yii::t('form', 'Дата активации'),
							'format' => ['date', 'php:Y-m-d H:m:s'],
							'filter'=>false,
						],
					],
				]);
				?>  
			</div>
		</div>
	</div>
</div>
