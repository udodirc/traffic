<?php
use yii\helpers\Html;
use yii\web\View;
use common\models\StaticContent;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title); ?></h1>
<br/>
<div class="card-columns">
<?= ListView::widget([
	'dataProvider' => $newsList,
	'options' => [],
	'layout' => "{pager}\n{items}\n",
	'itemView' => function ($model, $key, $index, $widget) 
	{
		return $this->render('partial/_news_list_item',['model' => $model]);
	},
	'pager' => [
		'options'=>['class'=>'pagination flex-wrap'],   // set clas name used in ui list of pagination
		'linkOptions' => ['class' => 'page-link'],
		'firstPageLabel'=>Yii::t('form', 'Первый'),   // Set the label for the "first" page button
		'lastPageLabel'=>Yii::t('form', 'Последний'),    // Set the label for the "last" page button
		//'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
		//'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
		'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
	],
]);
?> 	
</div>
