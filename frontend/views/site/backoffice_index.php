<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (isset($seo->meta_title)) ? $seo->meta_title : '';
$this->registerMetaTag([
    'name' => 'description',
    'content' => (isset($seo->meta_desc)) ? $seo->meta_desc : '',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => (isset($seo->meta_keywords)) ? $seo->meta_keywords : '',
]);
$this->params['breadcrumbs'][] = $this->title;

if($registerDate > 0)
{	
	/*$this->registerCssFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/css/jquery.countdown.css');
	$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/js/count_down/jquery.plugin.js',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/js/count_down/jquery.countdown.js',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/js/count_down/jquery.countdown-ru.js',['depends' => [\yii\web\JqueryAsset::className()]]);

	$now = new DateTime();
	
	$inlineScript = "$(function () {
		var austDay = new Date(".($registerDate * 1000).");
		var serverTime = '".$now->format("M j, Y H:i:s O")."';
		$('#count_down').countdown({until: austDay, serverSync: serverTime});
	});";
	$this->registerJs($inlineScript,  View::POS_END);*/
}
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success alert-dismissable">
	<span>
		<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
	</span>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger alert-dismissable">
	<span>
		<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
	</span>
</div><!-- /.flash-success -->
<?php endif; ?>
<div class="site-index">
	<!--  
	<div id="count_down"></div>
	-->
	<div class="row">
		<div class="col-lg-4">
			<div class="card input_text_fields m-t-35">
				<div class="card-header bg-white">
					<h2><?= Yii::t('form', 'Новости'); ?></h2>
				</div>
				<div class="card-block">
				<?= ListView::widget([
						'dataProvider' => $newsList,
						'options' => [
							'tag' => 'ul',
							'class' => 'list-unstyled timeline widget'
						],
						'layout' => "{pager}\n{items}\n",
						'itemView' => function ($model, $key, $index, $widget) {
							return $this->render('partial/_news_list_item',['model' => $model]);
						},
						'pager' => [
							'maxButtonCount' => 10,
						],
					]);
					?>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="card m-t-35">
					<div class="card-header bg-white">
						<h2><?= Html::encode($this->title); ?></h2>
					</div>
					<div class="card-block">
						<?= ($staticContent !== null) ? $staticContent->content : ''; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
