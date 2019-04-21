<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\structure\models\GoldTokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Выплата за личные приглашения');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invite-pay-off">
	<h1><?= Html::encode($this->title) ?></h1>
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute'=>'payer_login',
				'format'=>'text', // Возможные варианты: raw, html
				'filterInputOptions'=>['name' => 'InvitePayOffSearch[payer_login]'],
				'content'=>function($data){
					return $data->getPartnerName();
				},
			],
			[
				'attribute'=>'benefit_login',
				'format'=>'text', // Возможные варианты: raw, html
				'filterInputOptions'=>['name' => 'InvitePayOffSearch[benefit_login]'],
				'content'=>function($data){
					return $data->getBenfitPartnerName();
				},
			],
			[
				'attribute' => 'structure_number', 
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($structuresList) {
					return (isset($structuresList[$model->structure_number])) ? $structuresList[$model->structure_number] : '';
				},
			],
			'matrix_number',
			'matrix_id',
			'amount',
			[
				'attribute'=>'paid_off',
				'label' => Yii::t('form', 'Выплаты'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) 
				{
					if($model->paid_off > 0)
					{
						return Yii::t('form', 'Выплачено');
					}
					else
					{
						return '<div style="margin-bottom:10px;">'.Html::a(Yii::t('form', 'Сделать выплату'), \Yii::$app->request->BaseUrl.'/invite-payoff-list/make-pay-off?id='.$model->id.'&type=2',
						[
							'title' => Yii::t('form', 'Сделать выплату')
						])
						.'</div><div>'
						.Html::a(Yii::t('form', 'Отметить как выплату'), \Yii::$app->request->BaseUrl.'/invite-payoff-list/mark-pay-off?id='.$model->id,
						[
							'title' => Yii::t('form', 'Отметить как выплату')
						]).'</div>';
					}
				},
			],
			[
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата ативации'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
