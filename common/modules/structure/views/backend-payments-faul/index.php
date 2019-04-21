<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\structure\models\PaymentsFaulSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Ошибки платежей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-faul-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="notice error">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<div class="filter-form">
		<?php $form = ActiveForm::begin([
			'action'=>['index'],
			'method' => 'get',
		]);?>
		<div class="form-group" style="overflow:hidden;">
			<?= Html::submitButton(Yii::t('form', 'Очистить поиск'), ['class' => 'button-blue', 'onClick'=>'reset_form();']) ?>
		</div>
		<div class="selector">
		 <?= $form->field($searchModel, 'paid')->dropDownList(['0'=>Yii::t('form', 'Не оплатившие'), '1'=>Yii::t('form', 'Оплатившие')], ['prompt'=>Yii::t('form', 'Выбрать'), 'style'=>'width:200px;'])->label(Yii::t('form', 'Тип оплаты')); ?>
		</div>
		<div class="form-group">
			<?= Html::submitButton(Yii::t('form', 'Поиск'), ['class' => 'button-blue']) ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="actions_wrapper">
		<div class="action_wrapper">
			<?= Html::a(Yii::t('form', 'Выплата'), 
				['total-payment'], 
				[
					'class' => 'btn btn-success', 
					'data' => 
					[
						'confirm' => Yii::t('form', 'Вы хотите сделать выплату?'),
						'method' => 'post',
					]
				]
			);
			?>
		</div>
		<div class="action_wrapper">
			<?= Html::a(Yii::t('form', 'Написать сообщение'), 
				['message'], 
				[
					'class' => 'btn btn-success'
				]
			);
			?>
		</div>
		<div class="action_wrapper">
			<?= Html::a(Yii::t('form', 'Сравнить транзакции'), 
				['compare-transactions'], 
				[
					'class' => 'btn btn-success'
				]
			);
			?>
		</div>
	</div>
	<div class="actions_wrapper">
		<?= Yii::t('form', 'Общее количество платежей').' - '.$dataProvider->getTotalCount(); ?>
	</div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute' => 'structure_number', 
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($structuresList) {
					return (isset($structuresList[$model->structure_number])) ? $structuresList[$model->structure_number] : '';
				},
			],
            [
				'attribute' => 'matrix_number', 
				'label' => Yii::t('form', '№ матрицы'),
				'format'=>'raw',//raw, html
			],
            [
				'attribute' => 'matrix_id', 
				'label' => Yii::t('form', 'ID'),
				'format'=>'raw',//raw, html
			],
            [
				'attribute' => 'paid_matrix_id', 
				'label' => Yii::t('form', 'ID отправителя'),
				'format'=>'raw',//raw, html
			],
            'amount',
            [
				'attribute'=>'login_receiver',
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->login_receiver), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partner-info?id='.$model->partner_id, ['target'=>'blank']);
				},
			],
            [
				'attribute'=>'login_paid',
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->login_paid), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partner-info?id='.$model->paid_matrix_partner_id, ['target'=>'blank']);
				},
			],
            'note',
            [
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{view}',
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{pay}',
				'buttons' => [
					'pay' => function ($url, $model) 
					{
						if($model->paid > 0)
						{
							return Yii::t('form', 'Выплачено');
						}
						else
						{
							return Html::a('<span class="glyphicon glyphicon-usd"></span>', $url, [
								'title' => Yii::t('form', 'Выплата'),
								'data' => 
								[
									'confirm' => Yii::t('form', 'Вы хотите сделать выплату?'),
									'method' => 'post',
								]
							]);
						}
					}
				],
				'urlCreator' => function ($action, $model, $key, $index) 
				{
					if($model->paid == 0)
					{
						if ($action === 'pay') 
						{
							$baseUrl = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
							$url = ($baseUrl == 'index') ? '' : Yii::$app->controller->id.DIRECTORY_SEPARATOR;
							$url.= 'pay?id='.$model->id;
							return $url;
						}
					}
				}
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{activate}',
				'buttons' => [
					'activate' => function ($url, $model) 
					{
						if($model->paid > 0)
						{
							return Yii::t('form', 'Выплачено');
						}
						else
						{
							$icon = ($model->status > 0) ? 'inactive' : 'active';
							return Html::a('<span class="glyphicon glyphicon-status-'.$icon.'"></span>', $url, [
								'title' => Yii::t('form', 'Активация'),
							]);
						}
					}
				],
				'urlCreator' => function ($action, $model, $key, $index) 
				{
					if ($action === 'activate') 
					{
						$baseUrl = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME);
						$url = ($baseUrl == 'index') ? '' : Yii::$app->controller->id.DIRECTORY_SEPARATOR;
						$url.= 'status?id='.$model->id.'&status='.$model->status;
						return $url;
					}
				}
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
			]
        ],
    ]); ?>
</div>
