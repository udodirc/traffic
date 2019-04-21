<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\components\DropDownListHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('form', 'Сравнение кошельков');
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
            [
				'attribute'=>'advcash_wallet',
				'label' => Yii::t('form', 'Кошелек'),
				'format'=>'raw',//raw, html
				'value'=>function ($model) use ($defaultWallet) {
					$wallet = ($defaultWallet != '') ? $defaultWallet.'_wallet' : '';
					$wallet = ($wallet != '') ? $model->$wallet : '';
					
					return Html::a(Html::encode($wallet), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partners-list-by-wallet?wallet='.$wallet);
				},
			],
        ],
    ]);
?>
