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

if($sponsorAdvert !== null)
{
	$inlineScript = "jQuery(window).load(function () {
		showSponsorAdvertWindow();
	});";
	$this->registerJs($inlineScript,  View::POS_END);
}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Html::encode($this->title); ?></h5>
			</div>
            <div class="ibox-content">
				<div class="table-responsive">
					<?= GridView::widget([
						'dataProvider' => $dataProvider,
						//'filterModel' => $searchModel,
						'id'=>'structure',
						'class'=>'table table-striped table-bordered table-hover dataTables-example',
						'layout'=>"{pager}\n{summary}\n{items}",
						'rowOptions' => function ($model, $index, $widget, $grid)
						{
							if($model->matrix > 0)
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
									$status = ($partnerModel->matrix > 0) ? 'class="tr_active_status"' : '';
									$geoData = unserialize($partnerModel->geo);
									$iso = (isset($geoData['country']['iso'])) ? $geoData['country']['iso'] : '';
									$country = (isset($geoData['country']['name_ru'])) ? $geoData['country']['name_ru'] : '';
						
									return '<tr data-key="0" '.$status.'>
										<td>0</td>
										<td>'.$partnerModel->sponsor_name.'</td>
										<td>'.$partnerModel->login.'</td>
										<td>'.(($iso != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($iso).'.png'), ['alt'=>$country, 'title'=>$country]) : '').'</td>
										<td>'.date("Y-m-d H:i:s", $partnerModel->created_at).'</td>
									</tr>';
								}
							}
						},
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							'sponsor_name',
							'login',
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
								'attribute' => 'created_at', 
								'label' => Yii::t('form', 'Дата регистрации'),
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
</div>
<?php 
if($sponsorAdvert !== null):
\Yii::$app->session->set('user.sponsor_advert', 0);
?>
<?=$this->render('partial/sponsor_advert_window',[
	'sponsorAdvert' => $sponsorAdvert,
	'id' => $id,
]);?>
<?php endif; ?>
