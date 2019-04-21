<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-sm-12 col-md-12">
	<div class="panel">
		<div class="panel-header">
			<h4 class="list-title"><?= Yii::t('form', 'Информация'); ?></h4>
		</div>
		<div class="panel-content">
			<?php
			if(isset($newsAttention) && $newsAttention != null):
				echo $newsAttention->content;
			endif;
			?>
		</div>
	</div>
</div>
<div class="col-sm-12 col-md-12">
	<div class="panel">
		<div class="panel-header">
			<h4 class="list-title">
			<span class="left rm-25">
				<?= Html::encode($this->title); ?>
			</span>
		</div>
        <div class="panel-content">
			<div class="table-responsive">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					//'filterModel' => $searchModel,
					'id'=>'basic-table',
					'class'=>'data-table table table-striped nowrap table-hover',
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
					'beforeRow'=>function ($model, $key, $index, $grid) use ($partnerModel)
					{ 
						if($index == 0)
						{
							if($partnerModel != NULL)
							{
								$status = ($partnerModel['matrix_1'] > 0) ? 'class="tr_active_status"' : '';
								$geoData = unserialize($partnerModel['geo']);
								$iso = (isset($geoData['country']['iso'])) ? $geoData['country']['iso'] : '';
								$country = (isset($geoData['country']['name_ru'])) ? $geoData['country']['name_ru'] : '';
								
								return '<tr data-key="0" '.$status.'>
									<td>0</td>
									<td>'.((strlen($partnerModel['sponsor_name']) > 3) ? '****'.substr($partnerModel['sponsor_name'], 3) : '**'.substr($partnerModel['sponsor_name'], 1)).'</td>
									<td>'.$partnerModel['login'].'</td>
									<td>'.(($iso != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($iso).'.png'), ['alt'=>$country, 'title'=>$country]) : '').'</td>
									<td>'.date("Y-m-d H:i:s", $partnerModel['created_at']).'</td>
									<td>'.(($partnerModel['matrix_1'] > 0) ? Yii::t('form', 'Оплатил') : '').'</td>
									<td>'.$partnerModel['matrix_1'].'</td>
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
								return (strlen($model['sponsor_name']) > 3) ? '****'.substr($model['sponsor_name'], 3) : '**'.substr($model['sponsor_name'], 1);
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
								$country = (isset($isoList[$model['iso']])) ? $isoList[$model['iso']] : '';
								
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
