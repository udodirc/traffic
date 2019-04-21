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
$this->registerCssFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/css/font-icons/font-awesome/css/font-awesome.min.css');
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
<div class="last_register">
	<div class="row vspace"><!-- "vspace" class is added to distinct this row -->
		<div class="col-sm-12">
			<h3>
				<?= Yii::t('form', 'Последние регистрации:'); ?>
			</h3>
		</div>
	</div>
</div>
<div style="margin-bottom:40px;">
	<div class="container">
        <div class="row">
			<div class="moduletable home_post2  col-sm-12">
				<div class="content_wrap register_widget">
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
		</div>
	</div>
</div>
<div class="container">
	<div class="row vspace"><!-- "vspace" class is added to distinct this row -->
		<div class="col-sm-6">
			<div class="feature-block">
				<h3>
					<i class="fa fa-user"></i>
					<?= Yii::t('menu', 'Регистраций сегодня').' - '.((isset($this->params['curr_day_register']) && !empty($this->params['curr_day_register'])) ? $this->params['curr_day_register'] : 0); ?>
				</h3>
			</div>
		</div>	
		<div class="col-sm-6">
			<div class="feature-block">
				<h3>
					<i class="fa fa-users"></i>
					<?= Yii::t('menu', 'Всего партнеров').' - '.((isset($this->params['total_register'])) ? $this->params['total_register'] : 0); ?>
				</h3>
			</div>
		</div>
	</div>
</div>
<?= CounterWidget::widget([
	'options' => []
]);
?>
<div class="stuck_position">
	<div class="container">
        <div class="row">
			<div class="moduletable home_post2  col-sm-12">
				<?= ($staticContent !== null) ? $staticContent->content : ''; ?>
			</div>
		</div>
	</div>
</div>
