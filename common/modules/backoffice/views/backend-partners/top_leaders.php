<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = Yii::t('form', 'Топ лидеров');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-info-view">
    <div class="filter-form">
		<?php $form = ActiveForm::begin([
			'action'=>['backend-partners/top-leaders/index'],
			'method' => 'post',
			'id' => 'filter-form',
			'fieldConfig' =>
				[
					'options' => [
						'tag'=>'span'
					]
				],
		]);?>
        <div class="form-group" style="overflow:hidden;">
			<?= Html::submitButton(Yii::t('form', 'Очистить поиск'), ['class' => 'button-blue', 'onClick'=>'reset_form();']) ?>
        </div>
        <div class="selector">
			<?= $form->field($model, 'month', [
				'template' => '<div class="col-md-6">
				{label}{input}{hint}{error}
			</div>',
				'inputOptions' => []])->dropDownList($months, ['prompt'=>'Выбрать', 'style'=>'width:200px;'])->label(Yii::t('form', 'Месяц')); ?>
        </div>
        <div class="form-group" style="overflow:hidden;">
            <div style="float:right;">
				<?= Html::submitButton(Yii::t('form', 'Поиск'), ['class' => 'button-blue']) ?>
            </div>
        </div>
		<?php ActiveForm::end(); ?>
    </div>
	<h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
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
            'id',
            [
				'attribute'=>'login',
				'label' => Yii::t('form', 'Имя'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model['login']), 'partner-info?id='.$model['id']);
				},
			],
			'email',
			[
				'attribute'=>'referrals_count',
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
			        if($model->active_partners_count > 0 && $model->referrals_count > 0){
				        return round($model->active_partners_count / ($model->referrals_count / 100), 2).'%';
			        }
			        return 0;
		        },
	        ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
	?>
</div>
