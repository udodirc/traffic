<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;
use common\widgets\running_geo_data\RunningGeoDataWidget;
use common\modules\slider\widgets\SliderWidget;
use common\widgets\counter\CounterWidget;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (!is_null($seo)) ? $seo->meta_title : '';
$this->registerMetaTag([
    'name' => 'description',
    'content' => (!is_null($seo)) ? $seo->meta_desc : '',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => (!is_null($seo)) ? $seo->meta_keywords : '',
]);

\Yii::$app->view->registerMetaTag(["test", null, null, array('property' => "og:image")]);

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider">
	<?php if(!empty($sliderList)): ?>
	<?= SliderWidget::widget([
		'item_list' => $sliderList,
		'category' => 'slider',
		'slider_number' => '1',
		'options' => [
			'$AutoPlay'=>1,
			'$DragOrientation'=>3,
			'$ArrowNavigatorOptions'=>[
				'$Class'=>'$JssorArrowNavigator$',
				'$ChanceToShow'=>'2',
				'$Steps'=>'1',
			],
			'Fade'=>[
				'$Duration'=>'800',
				'$Opacity'=>'2',
			],
		],
	]);
	?>
	<?php endif; ?>
</div>
<section id="last-register" class="container wrap top">
	<div class="row">
        <div class="col-lg-12 align-center">
			<h2 class="last-register">
				<?= Yii::t('form', 'Последние регистрации:'); ?>
			</h2>
		</div>
    </div>
</section>
<section id="geo-data" class="container wrap">
	<div class="row">
        <div class="col-lg-12">
			<div class="simple-marquee-container">
				<div class="marquee">
					<?= RunningGeoDataWidget::widget([
						'item_list' => $partnersGeoDataList,
						'options' => ['duration'=>400000, 'hover'=>'false']
					]);
					?>
				</div>
			</div>
		</div>
    </div>
</section>
<section id="registrations" class="container wrap">
	<div class="row">
        <div class="col-lg-6 align-center">
			<h3>
				<i class="fa fa-user"></i>
				<?= Yii::t('menu', 'Регистраций сегодня').' - '.((isset($this->params['curr_day_register']) && !empty($this->params['curr_day_register'])) ? $this->params['curr_day_register'] : 0); ?>
			</h3>
		</div>
		<div class="col-lg-6 align-center">
			<h3>
				<i class="fa fa-users"></i>
				<?= Yii::t('menu', 'Всего партнеров').' - '.((isset($this->params['total_register'])) ? $this->params['total_register'] : 0); ?>
			</h3>
		</div>
    </div>
</section>
<?= ($staticContent !== null) ? $staticContent->content : ''; ?>
