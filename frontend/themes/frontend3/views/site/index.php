<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Index';
$this->params['breadcrumbs'][] = $this->title;

if($registerDate > 0)
{
	$this->registerCssFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/css/jquery.countdown.css');
	$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/js/count_down/jquery.plugin.js',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/js/count_down/jquery.countdown.js',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/js/count_down/jquery.countdown-ru.js',['depends' => [\yii\web\JqueryAsset::className()]]);

	$now = new DateTime();
	
	$inlineScript = "$(function () {
		var austDay = new Date(".($registerDate * 1000).");
		var serverTime = '".$now->format("M j, Y H:i:s O")."';
		$('#count_down').countdown({until: austDay, serverSync: serverTime});
	});";
	$this->registerJs($inlineScript,  View::POS_END);
}
?>
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
<div class="site-index">
	<div id="count_down"></div>
	<div>
		<?= ($staticContent !== null) ? $staticContent->content : ''; ?>
	</div>
	<div class="adverts_list">
		<?/*=$this->render('partial/adverts_list', [
			'advertsList' => $advertsList
		]);*/?>
	</div>
</div>
