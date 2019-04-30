<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= $this->title; ?></h2>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="card-body">
					<h4 class="card-title"><?= Yii::t('form', 'Информация'); ?></h4>
					<?= (isset($content) && $content != null) ? HtmlPurifier::process($content->content) : ''; ?>
				</div>  
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<?php if (Yii::$app->session->hasFlash('success')): ?>
			<div class="alert alert-success" role="alert">
				<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
			</div>
			<?php elseif (Yii::$app->session->hasFlash('error')): ?>
			<div class="alert alert-danger" role="alert">
				<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="card-body">
					<h4 class="card-title"><?= $this->title; ?></h4>
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
									return (strlen($model->login) > 3) ? '****'.Html::encode(substr($model->login, 3)) : '**'.Html::encode(substr($model->login, 1));
								},
							],
							[
								'attribute' => 'geo', 
								'label' => Yii::t('form', 'Страна'),
								'format'=>'raw',//raw, html
								'content'=>function ($model) use ($isoList)
								{
									$country = (isset($isoList[$model->iso])) ? Html::encode($isoList[$model->iso]) : '';
										
									return ($model->iso != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower($model->iso).'.png'), ['width'=>'30px', 'height'=>'30px', 'alt'=>$country, 'title'=>$country]) : '';
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
</div>
