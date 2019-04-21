<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\structure\models\PaymentsInvoicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Платежи');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(Yii::$app->request->baseUrl.DIRECTORY_SEPARATOR.\Yii::getAlias('@backend_admin_js_dir').DIRECTORY_SEPARATOR.'core.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="payments-invoices-index">
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
			<?= $form->field($searchModel, 'transact_id', [
			'template' => '<div class="col-md-6">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;'])->label(Yii::t('form', 'ID транзакции')); ?>
			<?= $form->field($searchModel, 'wallet', [
			'template' => '<div class="col-md-6">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;'])->label(Yii::t('form', 'Кошелек')); ?>
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
		<div class="form-group" style="overflow:hidden;">
			<div style="float:right;">
				<?= Html::submitButton(Yii::t('form', 'Поиск'), ['class' => 'button-blue']) ?>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="actions_wrapper">
		<?= Yii::t('form', 'Общее количество платежей').' - '.$dataProvider->getTotalCount(); ?>
	</div>
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
            'matrix_number',
            'matrix_id',
            [
				'attribute' => 'account_type', 
				'label' => Yii::t('form', 'Тип оплаты'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use($accountTypes) 
				{
					return isset($accountTypes[$model->account_type]) ? $accountTypes[$model->account_type] : '';
				}
			],
            'amount',
            [
				'attribute' => 'login_receiver', 
				'label' => Yii::t('form', 'Получатель'),
				'format'=>'raw',//raw, html
				'value'=>function ($model)
				{
					return ($model->account_type > 1) ? $model->login_receiver : '';
				}
			],
            'login_paid',
            [
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{view}',
			],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
