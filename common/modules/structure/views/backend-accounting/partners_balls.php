<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\modules\structure\models\Withdrawal;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Баллы');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-info-view">
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
            [
				'attribute'=>'login',
				'label' => Yii::t('form', 'Имя'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->login), 'partner-info?id='.$model->id);
				},
			],
			'email',
			[
				'attribute'=>'referral_balls',
				'label' => Yii::t('form', 'Бонус за приглашения'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return (($model->referral_balls > 0) ? $model->referral_balls.' - '.Html::a(Yii::t('form', 'подробнее'), 'referral-balls-list?id='.$model->id.'&demo=0') : 0);
				},
			],
			[
				'attribute'=>'close_balls',
				'label' => Yii::t('form', 'Бонус за закрытие матрицы'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return (($model->close_balls > 0) ? $model->close_balls : 0);
				},
			],
        ],
    ]);
	?>
</div>
