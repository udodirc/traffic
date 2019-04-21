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
<div class="banners">
	<div class="col-sm-12 col-md-12">
		<div class="panel">
			<div class="panel-header">
				<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
			</div>
			<div class="panel-content">
				<div class="image_row">
					<?=$this->render('partial/banners_list', [
						'category' => $category
					]);?>
				</div>
			</div>
		</div>
	</div>
</div>
