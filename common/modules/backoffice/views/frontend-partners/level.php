<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark"><?= Html::encode($this->title); ?></h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-body">
				<span class="right">
					<?= Html::a(Yii::t('form', 'Назад'), ['/partners/matrices'], ['class' => 'btn btn-success']) ?>
				</span>
			</div>	
			<div  class="panel-wrapper collapse in">
				<div  class="panel-body">
					<?php if($dataProvider !== null && $percent > 0):
						echo GridView::widget([
						'dataProvider' => $dataProvider,
						//'filterModel' => $searchModel,
						'layout'=>"{pager}\n{summary}\n{items}",
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
							[
								'attribute' => 'sponsor_name', 
								'label' => Yii::t('form', 'Спонсор'),
								'format'=>'raw',//raw, html
								'value'=>function ($model) use ($partnerModel)
								{
									return $partnerModel->login;
								},
								'filter'=>false,
							],
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
							[
								'attribute' => 'activation_date', 
								'label' => Yii::t('form', 'Дата активации'),
								'format' => ['date', 'php:Y-m-d H:m:s'],
								'filter'=>false,
							],
							[
								'attribute' => 'percentage', 
								'label' => Yii::t('form', '%'),
								'format'=>'raw',//raw, html
								'content'=>function ($model) use ($percent)
								{
									return $percent;
								},
								'filter'=>false,
							],
							'percent_amount',
						],
					]);
				endif;
				?>
				</div>
			</div>
		</div>
	</div>
</div>
