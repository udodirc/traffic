<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\modules\backoffice\models\Partners;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = $model['login'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Информация о партнере'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$partnerInfo = array_merge($partnerEarningsInfo, $matricesList);
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
    <p>
        <?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model['id']], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('form', 'Активировать статус'), ['activate-status', 'id' => $model['id']], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('form', 'Назначить бонус'), ['set-bonus', 'id' => $model['id']], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('form', 'Назначить место'), ['set-matrix', 'id' => $model['id']], ['class' => 'btn btn-success']) ?>
        <?= Html::a((($model['status'] >= 0) ? Yii::t('form', 'Бан') : Yii::t('form', 'Разбанить')), ['ban', 'id' => $model['id']], ['class' => 'btn btn-success']) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'id',
            [
				'attribute' => 'sponsor_name', 
				'label' => Yii::t('form', 'Имя споснсора'),
				'format'=>'raw',//raw, html
				'value'=>$model['sponsor_name'],
			],
			[
				'attribute' => 'login', 
				'label' => Yii::t('form', 'Логин'),
				'format'=>'raw',//raw, html
				'value'=>$model['login'],
			],
			[
				'attribute' => 'first_name', 
				'label' => Yii::t('form', 'Имя'),
				'format'=>'raw',//raw, html
				'value'=>$model['first_name'],
			],
			[
				'attribute' => 'last_name', 
				'label' => Yii::t('form', 'Фамилия'),
				'format'=>'raw',//raw, html
				'value'=>$model['last_name'],
			],
            'email',
            [
				'attribute' => 'advcash_wallet', 
				'label' => ((isset($defaultWallet['name'])) ? $defaultWallet['name'] : ''),
				'format'=>'raw',//raw, html
				'value'=>((isset($defaultWallet['index'])) ? $model[$defaultWallet['index'].'_wallet'] : ''),
			],
            /*[
				'attribute' => 'status', 
				'label' => Yii::t('form', 'Статус'),
				'format'=>'raw',//raw, html
				'value'=>(isset($statuses_list[$model['status']])) ? $statuses_list[$model['status']] : '',
			],*/
            [
				'attribute' => 'mailing', 
				'label' => Yii::t('form', 'Рассылка'),
				'format'=>'raw',//raw, html
				'value'=>($model['mailing'] > 0) ? Yii::t('form', 'Да') : Yii::t('form', 'Нет'),
			],
            [
				'attribute' => 'referals_count', 
				'label' => Yii::t('form', 'Кол-во рефералов'),
				'format'=>'raw',//raw, html
				'value'=>$referalsCount,
			],
			[
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата регистрации'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
			[
				'attribute' => 'updated_at', 
				'label' => Yii::t('form', 'Дата последнего посещения'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
			[
				'attribute' => 'ip', 
				'label' => Yii::t('form', 'IP - адрес'),
				'format'=>'raw',//raw, html
				'value'=>$model['ip'],
			],
			[
				'attribute' => 'country', 
				'label' => Yii::t('form', 'Страна'),
				'format'=>'raw',//raw, html
				'value'=>(!empty($geoData) && isset($geoData['country']['name_ru'])) ? $geoData['country']['name_ru'] : '',
			],
			[
				'attribute' => 'region', 
				'label' => Yii::t('form', 'Регион'),
				'format'=>'raw',//raw, html
				'value'=>(!empty($geoData) && isset($geoData['region']['name_ru'])) ? $geoData['region']['name_ru'] : '',
			],
			[
				'attribute' => 'city', 
				'label' => Yii::t('form', 'Город'),
				'format'=>'raw',//raw, html
				'value'=>(!empty($geoData) && isset($geoData['city']['name_ru'])) ? $geoData['city']['name_ru'] : '',
			],
			[
				'attribute' => 'gold_token', 
				'label' => Yii::t('form', 'Общая сумма по бонусу за жетоны'),
				'format'=>'raw',//raw, html
				'value'=>($goldTokenSum > 0) ? $goldTokenSum : 0,
			],
        ], $partnerInfo),
    ]) ?>
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
				'attribute'=>'structure',
				'label' => Yii::t('form', 'Структура'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return Html::a(Html::encode($model->login), 'structure?id='.$model->id);
				},
			],
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
				'attribute'=>'matrix',
				'label' => Yii::t('form', 'Статус'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) {
					return ($model->matrix_1 > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен');
				},
			],
			[
				'attribute' => 'created_at', 
				'label' => Yii::t('form', 'Дата выплаты'),
				'format' => ['date', 'php:Y-m-d H:m:s'],
				'filter'=>false,
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
	?>
</div>
