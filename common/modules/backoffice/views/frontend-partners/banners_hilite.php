<?php
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$inlineScript = "if(!window.showAds) {
    // Your user is not using adblocker
    alert('Отключите adblock в браузере!');
}";
$this->registerJs($inlineScript,  View::POS_END);
?>
<h2><?= $this->title; ?></h2>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="card-body">
					<h4 class="card-title"><?= $this->title; ?></h4>
					<?=$this->render('partial/banners_list'.$theme, [
						'category' => $category
					]);?>
				</div>  
			</div>
		</div>
	</div>
</div>
