<?php
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$inlineScript = "if(!window.showAds) {
    // Your user is not using adblocker
    alert('Отключите adblock в браузере!');
}";
$this->registerJs($inlineScript,  View::POS_END);
?>
<h2><?= Html::encode($this->title); ?></h2>
<br/>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
					<?= Html::encode($this->title); ?>
				</div>
			</div>
			<div class="panel-body">
				<?=$this->render('partial/banners_list', [
					'category' => $category
				]);?>
			</div>
		</div>
	</div>
</div>
