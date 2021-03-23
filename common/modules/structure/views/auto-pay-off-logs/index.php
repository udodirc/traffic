<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\components\DropDownListHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\structure\models\GoldTokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Список авто платежей');
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
			<?= $form->field($searchModel, 'amount', [
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
			<?= $form->field($searchModel, 'type', [
			'template' => '<div class="col-md-6">
				{label}{input}{hint}{error}
			</div>',
			'inputOptions' => []])->dropDownList($paymentTypeList, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
			<?= $form->field($searchModel, 'paid_off', [
			'template' => '<div class="col-md-6">
				{label}{input}{hint}{error}
			</div>',
			'inputOptions' => []])->dropDownList($payOffList, ['prompt'=>'Выбрать', 'style'=>'width:200px;']); ?>
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
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
				'attribute'=>'partner_id',
				'label'=>Yii::t('form', 'Логин'),
				'format'=>'text', // Возможные варианты: raw, html
				'filterInputOptions'=>['name' => 'PayeerPaymentsSearch[login]'],
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
			[
				'attribute' => 'type', 
				//'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($paymentTypeList) {
					return (isset($paymentTypeList[$model->type])) ? $paymentTypeList[$model->type] : '';
				},
			],
			[
				'attribute' => 'paid_off', 
				//'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($payOffList) 
				{
					if($model->paid_off > 0)
					{
						return Yii::t('form', 'Выплачено');
					}
					else
					{
						return '<div style="margin-bottom:10px;">'.Html::a(Yii::t('form', 'Сделать выплату'), \Yii::$app->request->BaseUrl.'/auto-pay-off-logs/make-pay-off?id='.$model->id.'&type=3',
						[
							'title' => Yii::t('form', 'Сделать выплату')
						])
						.'</div><div>'
						.Html::a(Yii::t('form', 'Отметить как выплату'), \Yii::$app->request->BaseUrl.'/auto-pay-off-logs/mark-pay-off?id='.$model->id,
						[
							'title' => Yii::t('form', 'Отметить как выплату')
						]).'</div>';
					}
				},
			],
			'amount',
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
