<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\components\DropDownListHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\structure\models\GoldTokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Список баллов');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payeer-payments">
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
			<?= $form->field($searchModel, 'id', [
			'template' => '<div class="col-md-4">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;']); ?>
			<?= $form->field($searchModel, 'login', [
			'template' => '<div class="col-md-4">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;'])->label(Yii::t('form', 'Логин')); ?>
			<?= $form->field($searchModel, 'balls', [
			'template' => '<div class="col-md-4">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;']); ?>
		</div>
		<div class="selector">
			<?= $form->field($searchModel, 'structure_number', [
			'template' => '<div class="col-md-4">
				{label}{input}{hint}{error}
			</div>',
			'inputOptions' => []])->dropDownList($structuresList, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
			<?= $form->field($searchModel, 'matrix_number', [
			'template' => '<div class="col-md-4">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;']); ?>
			<?= $form->field($searchModel, 'matrix_id', [
			'template' => '<div class="col-md-4">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;']); ?>
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
			])->label(Yii::t('form', 'Дата оплаты от:')); ?>
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
			])->label(Yii::t('form', 'Дата оплаты до:')); ?>
		</div>
		<div class="form-group" style="overflow:hidden;">
			<div style="float:right;">
				<?= Html::submitButton(Yii::t('form', 'Поиск'), ['class' => 'button-blue']) ?>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute'=>'partner_id',
				'label'=>Yii::t('form', 'Логин'),
				'format'=>'text', // Возможные варианты: raw, html
				'filterInputOptions'=>['name' => 'BallsSearch[login]'],
				'content'=>function($data){
					return $data->getPartnerName();
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
			'balls',
			[
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата оплаты'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
