<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\components\ContentHelper;

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
			<div class="card-body">
				<h4 class="card-title"><?= Yii::t('form', 'Информация'); ?></h4>
				<?= ContentHelper::outPutContent($content);?>
			</div>  
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 offset-md-3 grid-margin stretch-card">
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
			<div class="card-body">
				<h4 class="card-title"><?= $this->title; ?></h4>
				<div class="table-responsive">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'class'=>'dataTables_wrapper container-fluid dt-bootstrap4 no-footer',
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
					'rowOptions' => function ($model, $key, $index, $grid)
					{
						if($index <= 10)
						{
							return ['class' => 'tr_top_10'];
						}
                        elseif ($index > 10 && $index <= 50)
                        {
	                        return ['class' => 'tr_top_50'];
                        }
						else
						{
							return [];
						}
					},
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
						[
							'attribute'=>'active_partners_count',
							'label' => Yii::t('form', 'Из них активных'),
							'format'=>'raw',//raw, html
						],
						[
							'attribute' => 'efficiency',
							'label' => Yii::t('form', 'КПД'),
							'format'=>'raw',//raw, html
							'content'=>function ($model)
							{
								return round($model->active_partners_count / ($model->referals_count / 100), 2).'%';
							},
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
