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
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success fade in">
	<a href="#" class="close" data-dismiss="alert">×</a>
	<span>
		<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
	</span>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger fade in">
	<a href="#" class="close" data-dismiss="alert">×</a>
	<span>
		<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
	</span>
</div><!-- /.flash-success -->
<?php endif; ?>
<div class="col-sm-6 col-md-6">
	<div class="panel">
		<div class="panel-header">
			<h4 class="list-title"><?= Yii::t('form', 'Новости'); ?></h4>
		</div>
        <div class="panel-content widget-list">
        <?= ListView::widget([
			'dataProvider' => $newsList,
				'options' => [
					'tag' => 'ul',
					'class' => 'ul'
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
<div class="col-sm-6 col-md-6">
	<div class="panel">
		<div class="panel-header">
			<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
		</div>
        <div class="panel-content">
        <?= ($staticContent !== null) ? $staticContent->content : ''; ?>               
		</div>
	</div>
</div>
