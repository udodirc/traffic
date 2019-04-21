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
<div class="top-leaders">
	<div class="col-sm-6 col-md-6">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
				<div class="table-responsive">
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
							'label' => Yii::t('form', 'Логин'),
							'format'=>'raw',//raw, html
							'content'=>function ($model)
							{
								return (strlen($model->login) > 3) ? '****'.substr($model->login, 3) : '**'.substr($model->login, 1);
							},
							'filter'=>false,
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
</div>
