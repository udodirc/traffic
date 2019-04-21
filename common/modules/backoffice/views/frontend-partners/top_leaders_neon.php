<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::encode($this->title); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					//'filterModel' => $searchModel,
					//'layout'=>"{pager}\n{summary}\n{items}",
					'pager' => [
						//'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
						'firstPageLabel'=>Yii::t('form', 'Первый'),   // Set the label for the "first" page button
						'lastPageLabel'=>Yii::t('form', 'Последний'),    // Set the label for the "last" page button
						'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
						'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
						'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
					],
					'columns' => [
						//['class' => 'yii\grid\SerialColumn'],
						[
							'attribute' => 'login', 
							//'label' => Yii::t('form', 'Логин спонсора'),
							'format'=>'raw',//raw, html
							'content'=>function ($model)
							{
								return (strlen($model->login) > 3) ? '****'.substr($model->login, 3) : '**'.substr($model->login, 1);
							},
						],
						[
							'attribute' => 'geo', 
							'label' => Yii::t('form', 'Страна'),
							'format'=>'raw',//raw, html
							'content'=>function ($model) use ($isoList)
							{
								$country = (isset($isoList[$model->iso])) ? $isoList[$model->iso] : '';
									
								return ($model->iso != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($model->iso).'.png'), ['alt'=>$country, 'title'=>$country]) : '';
							},
							'filter'=>false,
						],
						[
							'attribute'=>'referals_count',
							'label' => Yii::t('form', 'Кол-во рефералов'),
							'format'=>'raw',//raw, html
						],
							//['class' => 'yii\grid\ActionColumn'],
					],
				]);
				?>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Информация'); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>
			</div>
		</div>
	</div>
</div>
