<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\grid\GridView;
use common\components\ContentHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
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
                    <div class="col-lg-6">
						<div class="border-bottom text-center pb-6">
							<img src="https://via.placeholder.com/92x92" alt="profile" class="img-lg rounded-circle mb-3"/>
							<div class="mb-3">
								<h3><?= Html::encode(((\Yii::$app->user->identity !== null) ? Html::encode(\Yii::$app->user->identity->firstName).' '.Html::encode(\Yii::$app->user->identity->lastName) : '')); ?></h3>
							</div>
							<div class="d-flex justify-content-center">
								<?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => (isset($model['id'])) ? $model['id'] : 0], ['class' => 'btn btn-success mr-1']) ?>
								<?= Html::a(Yii::t('form', 'Сменить пароль'), ['change-password', 'id' => (isset($model['id'])) ? $model['id'] : 0], ['class' => 'btn btn-success']) ?>
							</div>
						</div>
						<div class="py-6">
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Логин'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($model['login'])) ? Html::encode($model['login']) : ''); ?>
								</span>
							</p>
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Имя'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($model['first_name'])) ? Html::encode($model['first_name']) : ''); ?>
								</span>
							</p>
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Фамилия'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($model['last_name'])) ? Html::encode($model['last_name']) : ''); ?>
								</span>
							</p>
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Email'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($model['email'])) ? Html::encode($model['email']) : ''); ?>
								</span>
							</p>
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Payeer кошелек'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($model['payeer_wallet'])) ? Html::encode($model['payeer_wallet']) : ''); ?>
								</span>
							</p>
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Статус'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($statuses_list[$model['status']])) ? Html::encode($statuses_list[$model['status']]) : ''); ?>
								</span>
							</p>
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Рассылка'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($model['mailing']) && $model['mailing'] > 0) ? Yii::t('form', 'Есть') : Yii::t('form', 'Нет')); ?>
								</span>
							</p>
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Дата создания'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode((isset($model['created_at'])) ? date('m-d-Y', Html::encode($model['created_at'])) : ''); ?>
								</span>
							</p>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="border-bottom text-center pb-6">
							<div class="mb-3">
								<h3><?= Yii::t('form', 'Статистика'); ?></h3>
							</div>
						</div>
						<div class="py-6">
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Кол-во рефералов'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode($referalsCount); ?>
								</span>
							</p>
						</div>
						<div class="py-6">
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Всего заработано баллов - РЕАЛЬНЫЙ ЗАРАБОТОК'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode($model['total_balls_1']); ?>
								</span>
							</p>
						</div>
						<div class="py-6">
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Всего заработано баллов - ОЖИДАЕМЫЙ ЗАРАБОТОК'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode($model['demo_total_balls_1']); ?>
								</span>
							</p>
						</div>
						<div class="py-6">
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Всего заработано - РЕАЛЬНЫЙ ЗАРАБОТОК'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode($model['total_amount_1']); ?>
								</span>
							</p>
						</div>
						<div class="py-6">
							<p class="clearfix">
								<span class="float-left">
									<?= Yii::t('form', 'Всего заработано - ОЖИДАЕМЫЙ ЗАРАБОТОК'); ?>
								</span>
								<span class="float-right text-muted">
									<?= Html::encode($model['demo_total_amount_1']); ?>
								</span>
							</p>
						</div>
						<?php foreach($partnerEarningsInfo as $i => $partnerData): ?>
						<div class="py-6">
							<p class="clearfix">
								<span class="float-left">
									<?= HtmlPurifier::process($partnerData['label']); ?>
								</span>
								<span class="float-right text-muted">
									<?= HtmlPurifier::process($partnerData['value']); ?>
								</span>
							</p>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top:30px;">
	<div class="col-lg-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= Yii::t('form', 'Лично приглашенные рефералы'); ?></h4>
				<div class="table-responsive">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					//'filterModel' => $searchModel,
					'id'=>'basic-table',
					'class'=>'dataTables_wrapper container-fluid dt-bootstrap4 no-footer',
					'layout'=>"{items}\n{pager}",
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
						'options'=>['class'=>'pagination flex-wrap'],   // set clas name used in ui list of pagination
						'linkOptions' => ['class' => 'page-link'],
						'firstPageLabel'=>Yii::t('form', 'Первый'),   // Set the label for the "first" page button
						'lastPageLabel'=>Yii::t('form', 'Последний'),    // Set the label for the "last" page button
						//'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
						//'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
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
								$country = (isset($isoList[$model['iso']])) ? Html::encode($isoList[$model['iso']]) : '';
								
								return ($model['iso'] != '') ? Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@backoffice_images'.DIRECTORY_SEPARATOR.'flags'.DIRECTORY_SEPARATOR.strtolower(Html::encode($model['iso'])).'.png'), ['alt'=>$country, 'title'=>$country]) : '';
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
</div>
