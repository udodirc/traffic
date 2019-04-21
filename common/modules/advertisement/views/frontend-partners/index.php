<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stucture">
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
    <!-- Основная часть -->
	<div class="reviews_list">
		<div class="add_review">
			<div class="add_review_link"><a href="text-advert/add_advert/<?= $partner_id; ?>"><?= Yii::t('form', 'Добавить рекламу'); ?></a></div>
		</div>
		<div class="items">
		<?= ListView::widget([
			'dataProvider' => $dataProvider,
			'options' => [
				'tag' => 'div',
				'class' => 'list-wrapper',
				'id' => 'list-wrapper',
			],
			'layout' => "{pager}\n{items}\n",
			'itemView' => function ($model, $key, $index, $widget) {
				return $this->render('_review_list_item',['model' => $model]);
			},
			'pager' => [
				'maxButtonCount' => 10,
			],
		]);
		?>
		</div>
	</div>
</div>
