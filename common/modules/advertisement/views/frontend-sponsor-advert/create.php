<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\advertisement\models\SponsorAdvert */

$this->title = Yii::t('form', 'Спонсорская реклама');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsor-advert-create">
	<div class="col-md-12">
		<div class="content-box-large">
			<div class="panel-heading">
				<h2><?= $this->title; ?></h2>
			</div>
			<?= $this->render('_form', [
				'model' => $model,
				'category' => $category,
				'url' => $url,
				'thumbnail' => $thumbnail,
				'id' => 0
			]) ?>
		</div>
	</div>
</div>
