<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Список выплат за личные приглашения');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referral-balls-list">
	<h1><?= Html::encode($this->title) ?></h1>
	<div style="width:100%; overflow:hidden; margin-bottom:10px;">
		<div style="float:right; margin-right:10px;">
			<?= Html::a(Yii::t('form', (($demo > 0) ? 'Реальная структура' : 'Демо')), ['invite-payoff-list', 'demo' => ($demo > 0) ? 0 : 1], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
            'id',
            [
				'attribute'=>'payer_login',
				'label' => Yii::t('form', 'Логин плательщика'),
				'value' => 'partner.login',
			],
            [
				'attribute'=>'benefit_login',
				'label' => Yii::t('form', 'Логин получателя'),
				'value' => 'benefitPartner.login',
			],
            'matrix_number',
            'matrix_id',
            'amount',
            [
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
        ],
    ]);
	?>
</div>
