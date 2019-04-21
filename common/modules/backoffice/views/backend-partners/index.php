<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\components\DropDownListHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Структура партнеров');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(Yii::$app->request->baseUrl.DIRECTORY_SEPARATOR.\Yii::getAlias('@backend_admin_js_dir').DIRECTORY_SEPARATOR.'core.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="structure-index">
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
			<?= $form->field($searchModel, 'country', [
			'template' => '<div class="col-md-6">
				{label}{input}{hint}{error}
			</div>',
			'inputOptions' => []])->dropDownList($countryList, ['prompt'=>'Выбрать', 'style'=>'width:200px;'])->label(Yii::t('form', 'Страна')); ?>
			<?= $form->field($searchModel, 'wallet', [
			'template' => '<div class="col-md-6">
				<div class="controls">
					{label}{input}{hint}{error}
				</div>
			</div>',
			'inputOptions' => []])->textInput(['style'=> 'width:200px;'])->label(Yii::t('form', 'Кошелек')); ?>
		</div>
		<div class="form-group" style="overflow:hidden;">
			<div style="float:right;">
				<?= Html::submitButton(Yii::t('form', 'Поиск'), ['class' => 'button-blue']) ?>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
	<div style="width:100%; overflow:hidden; margin-bottom:10px;">
		<div style="float:right; margin-right:10px;">
			<?= Html::a(Yii::t('form', 'Добавить партнера'), ['add-partner-in-structure'], ['class' => 'btn btn-success']) ?>
		</div>
		<div style="float:right; margin-right:10px;">
			<?= Html::a(Yii::t('form', 'Добавить партнеров'), ['add-partners-in-structure'], ['class' => 'btn btn-success']) ?>
		</div>
		<div style="float:right; margin-right:10px;">
			<?= Html::a(Yii::t('form', 'Активировать партнеров'), ['activate-partners-in-structure'], ['class' => 'btn btn-success']) ?>
		</div>
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
            'sponsor_name',
            [
				'attribute'=>'structure',
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->login), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-partners/').'structure?id='.$model->id);
				},
			],
			[
				'attribute'=>'login',
				'label' => Yii::t('form', 'Логин'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->login), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-partners/').'partner-info?id='.$model->id);
				},
			],
			'email',
			[
				'attribute'=>'activate',
				'label' => Yii::t('form', 'Акт-ть'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) 
				{
					if($model->matrix_1 > 0)
					{
						return Yii::t('form', 'Активен');
					}
					else
					{
						return Html::a(Yii::t('form', 'Акт-ть'), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-partners/').'activate?id='.$model->id.'&pay=1',
						[
							'title' => Yii::t('form', 'Активировать'),
							/*'data' => 
							[
								'confirm' => Yii::t('form', 'Вы хотите сделать активацию?'),
								'method' => 'post',
							]*/
						]);
					}
				},
			],
			[
				'attribute'=>'reserve',
				'label' => Yii::t('form', 'Бронь'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) 
				{
					return Html::a(Yii::t('form', 'Бронь'), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-partners/').'reserve?id='.$model->id);
				},
			],
			[
				'attribute'=>'activate',
				'label' => Yii::t('form', 'БВ'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) 
				{
					return Html::a(Yii::t('form', 'БВ'), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-partners/').'activate?id='.$model->id.'&pay=0',
					[
						'title' => Yii::t('form', 'Активировать'),
						'data' => 
						[
							'confirm' => Yii::t('form', 'Вы хотите сделать активацию?'),
							'method' => 'post',
						]
					]);
				},
			],
			/*[
				'attribute'=>'change_sponsor',
				'label' => Yii::t('form', 'Сменить'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) 
				{
					return Html::a(Yii::t('form', 'Сменить'), ((strpos($_SERVER['REQUEST_URI'], 'index')) ? '' : 'backend-partners/').'change-sponsor?id='.$model->id);
				},
			],*/
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{activate}',
				'buttons' => [
					'activate' => function ($url, $model) 
					{
						$icon = ($model->status > 0) ? 'inactive' : 'active';
						return Html::a('<span class="glyphicon glyphicon-status-'.$icon.'"></span>', $url, [
							'title' => Yii::t('form', 'Активация'),
						]);
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
				'template'=>'{update}',
			],
        ],
    ]);
?>
