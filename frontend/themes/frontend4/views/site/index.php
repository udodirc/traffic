<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;
use common\widgets\running_geo_data\RunningGeoDataWidget;

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
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main_wrap">
	<div class="content_wrap">
		<div class="register_info">
			<div class="register_count">
				<div class="total_register bold"><?= Yii::t('menu', 'Регистраций сегодня'); ?></div>
				<div class="total_register"><?= (isset($this->params['curr_day_register']) && !empty($this->params['curr_day_register'])) ? $this->params['curr_day_register'] : 0; ?></div>
			</div>
			<div class="register_count">
				<div class="total_register bold"><?= Yii::t('menu', 'Всего партнеров'); ?></div>
				<div class="total_register"><?= (isset($this->params['total_register'])) ? $this->params['total_register'] : 0; ?></div>
			</div>
		</div>
		<div class="main-content">
			<?= ($staticContent !== null) ? $staticContent->content : ''; ?>
		</div>
	</div>
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
