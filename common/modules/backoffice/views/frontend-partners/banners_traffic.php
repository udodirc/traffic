<?php
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$inlineScript = "if(!window.showAds) {
    // Your user is not using adblocker
    alert('Switch off ads block in your browser!');
}";
$this->registerJs($inlineScript,  View::POS_END);
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Html::encode($this->title); ?></h5>
			</div>
            <div class="ibox-content">
				<?=$this->render('partial/banners_list', [
						'category' => $category
					]);?>
			</div>
		</div>
	</div>
</div>
