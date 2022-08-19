<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title; ?></h1>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
					<h4 class="card-title"><?= Yii::t('form', 'Информация'); ?></h4>
					<p class="card-description">
						<?= (isset($newsAttention) && $newsAttention != null) ? HtmlPurifier::process($newsAttention->content) : ''; ?>
					</p>
				</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= $this->title; ?></h4>
				<div class="table-responsive">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					//'filterModel' => $searchModel,
					'id'=>'basic-table',
					'class'=>'dataTables_wrapper container-fluid dt-bootstrap4 no-footer',
					'layout'=>'
					<div class="row"><div class="col-sm-12">{items}</div></div>
					<div class="row">
						<div class="col-sm-12 col-md-5">
							<div class="dataTables_info" id="order-listing_info" role="status" aria-live="polite">{summary}</div>
						</div>
						<div class="col-sm-12 col-md-7">
							<div class="dataTables_paginate paging_simple_numbers" id="order-listing_paginate">{pager}</div>
						</div>
					</div>',
					'options'=>['class'=>'dataTables_wrapper container-fluid dt-bootstrap4 no-footer'],
					'tableOptions' =>['class' => 'table dataTable no-footer'],
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
						'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
						'linkOptions' => ['class' => 'page-link'],
						'prevPageLabel' => '<<',   // Set the label for the “previous” page button
						'nextPageLabel' => '>>',   // Set the label for the “next” page button
						'firstPageLabel'=> Yii::t('form', 'Первый'),   // Set the label for the "first" page button
						'lastPageLabel' => Yii::t('form', 'Последний'),    // Set the label for the "last" page button
						//'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
						//'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
						'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
					],
					'beforeRow'=>function ($model, $key, $index, $grid) use ($partnerModel)
					{ 
						if($index == 0)
						{
							if($partnerModel != NULL)
							{
								$status = ($partnerModel['matrix_1'] > 0) ? 'class="tr_active_status"' : '';
								$geoData = unserialize($partnerModel['geo']);
								$iso = (isset($geoData['country']['iso'])) ? Html::encode($geoData['country']['iso']) : '';
								$country = (isset($geoData['country']['name_ru'])) ? Html::encode($geoData['country']['name_ru']) : '';
										
								return '<tr data-key="0" '.$status.'>
								<td>0</td>
									<td>'.(($partnerModel['sponsor_name'] != null) ? ((strlen($partnerModel['sponsor_name']) > 3) ? '****'.Html::encode(substr($partnerModel['sponsor_name'], 3)) : '**'.Html::encode(substr($partnerModel['sponsor_name'], 1))) : '').'</td>
									<td>'.Html::encode($partnerModel['login']).'</td>
									<td>'.(($iso != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($iso).'.png'), ['alt'=>$country, 'title'=>$country]) : '').'</td>
									<td>'.date("Y-m-d H:i:s", Html::encode($partnerModel['created_at'])).'</td>
									<td>'.(($partnerModel['matrix_1'] > 0) ? Yii::t('form', 'Оплатил') : '').'</td>
									<td>'.Html::encode($partnerModel['matrix_1']).'</td>
								</tr>';
							}
						}
					},
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						[
							'attribute' => 'sponsor_name', 
							'label' => Yii::t('form', 'Логин спонсора'),
							'format'=>'raw',//raw, html
							'content'=>function ($model)
							{
								return (strlen($model['sponsor_name']) > 3) ? '****'.Html::encode(substr($model['sponsor_name'], 3)) : '**'.Html::encode(substr($model['sponsor_name'], 1));
							},
						],
						[
							'attribute' => 'login', 
							'label' => Yii::t('form', 'Логин'),
							'format'=>'raw',//raw, html
						],
						[
							'attribute' => 'geo', 
							'label' => Yii::t('form', 'Страна'),
							'format'=>'raw',//raw, html
							'content'=>function ($model) use ($isoList)
							{
									$country = (isset($isoList[$model['iso']])) ? Html::encode($isoList[$model['iso']]) : '';
									
								return ($model['iso'] != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($model['iso']).'.png'), ['alt'=>$country, 'title'=>$country]) : '';
							},
								'filter'=>false,
						],
						[
							'attribute' => 'created_at', 
							'label' => Yii::t('form', 'Дата регистрации'),
							'format' => ['date', 'php:Y-m-d H:m:s'],
							'filter'=>false,
						],
						[
							'attribute' => 'matrix', 
							'label' => Yii::t('form', 'Активация'),
							'format'=>'raw',//raw, html
							'content'=>function ($model)
							{
								return ($model['matrix_1'] > 0) ? Yii::t('form', 'Оплатил') : '';
							},
							'filter'=>false,
						],
						[
							'attribute' => 'matrix_1', 
							'label' => Yii::t('form', 'Матрица'),
							'format'=>'raw',//raw, html
						],
					],
				]);
				?>
				</div>
			</div>
		</div>
	</div>
</div>
