<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\components\DropDownListHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Запросы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets">
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
			<?= $form->field($searchModel, 'date_from', [
			'template' => '<div class="col-md-6">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->widget(\yii\jui\DatePicker::className(), [
				'language' => 'ru',
				'dateFormat' => 'dd-MM-yyyy',
				'options' => ['style'=>'width:200px;'],
			])->label(Yii::t('form', 'Дата от:')); ?>
			<?= $form->field($searchModel, 'date_to', [
			'template' => '<div class="col-md-6">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->widget(\yii\jui\DatePicker::className(), [
				'language' => 'ru',
				'dateFormat' => 'dd-MM-yyyy',
				'options' => ['style'=>'width:200px;'],
			])->label(Yii::t('form', 'Дата до:')); ?>
		</div>
		<div class="selector">
		 <?= $form->field($searchModel, 'status')->dropDownList(['0'=>Yii::t('form', 'Не активные'), '1'=>Yii::t('form', 'Активные')], ['prompt'=>Yii::t('form', 'Выбрать'), 'style'=>'width:200px;'])->label(Yii::t('form', 'Статус')); ?>
		</div>
		<div class="form-group">
			<?= Html::submitButton(Yii::t('form', 'Поиск'), ['class' => 'button-blue']) ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="actions_wrapper">
		<?= Yii::t('form', 'Общее количество запросов').' - '.$dataProvider->getTotalCount(); ?>
	</div>
	<div style="width:100%; overflow:hidden; margin-bottom:10px;">
		<div style="float:right; margin-right:10px;">
			<?= Html::a(Yii::t('form', 'Создать запрос'), ['create'], ['class' => 'btn btn-success']) ?>
		</div>
		<div style="float:right; margin-right:10px;">
			<?= Html::a(Yii::t('form', 'Рассылка'), ['mailing'], ['class' => 'btn btn-success']) ?>
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
				'attribute' => 'partner_id', 
				'label' => Yii::t('form', 'Логин'),
				'format'=>'raw',//raw, html
				'filterInputOptions'=>['name' => 'SearchTickets[login]'],
				'value'=>function ($model) {
					return $model->login;
				},
			],
			[
				'attribute' => 'subject', 
				'label' => Yii::t('form', 'Тема'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->subject), 'ticket/'.$model->id);
				},
			],
			[
				'attribute'=>'status',
				'label' => Yii::t('form', 'Статус'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return ($model->status > 0) ? Yii::t('form', 'Ожидайте ответа') : Yii::t('form', 'Ожидает вашего ответа');
				},
				'filter'=>false,
			],
			[
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Создан'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
			 [
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{delete}',
			],
		],
	]);
	?> 
</div>	
