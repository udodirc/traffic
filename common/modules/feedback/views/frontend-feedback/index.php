<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['meta_title'])) ? $this->params['meta_title'] : 'Отзывы';
$this->registerMetaTag([
    'name' => 'description',
    'content' => (isset($this->params['meta_tags'])) ? $this->params['meta_tags'] : 'Отзывы|Без админа! "Всемирная касса взаимопомощи"!',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => (isset($this->params['meta_keywords'])) ? $this->params['meta_keywords'] : 'отзывы, без админа, withoutadmin, заработок в сети, заработок в интернете, новый проект, мгновенные денежные переводы, матрица, млм, mlm',
]);
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="moduletable col-sm-12">
				<div class="module_container">
					<ul class="breadcrumb">
						<li class="active"><span><?= Yii::t('form', 'Отзывы'); ?></span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="content">
	<div class="container">
		<div class="row">
			<div class="content-inner">
				<!-- Left sidebar -->
				<div id="component" class="col-sm-12">
					<main role="main">
						<div id="system-message-container"></div>
						<section class="page-category page-category__history">
							<header class="page_header">
								<h2 class="heading-style-2 visible-first"><span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first item_title_part_last"><?= Yii::t('form', 'Отзывы'); ?></span></h2>
							</header>
							<?php if($feedbackList->totalCount > 0): ?>
								<?= ListView::widget([
									'dataProvider' => $feedbackList,
									'options' => [
										'id' => false,
									],
									'itemOptions' => [
										'tag' => false,
									],
									'layout' => "{pager}\n{items}\n",
									'itemView' => function ($model, $key, $index, $widget) {
										return $this->render('partial/_feedback_list_item',['model' => $model]);
									},
									'pager' => [
										'maxButtonCount' => 10,
									],
								]);
								?>
							<?php endif; ?>
						</section>
					</main>
				</div>
			</div>
		</div>
	</div>
</div>
