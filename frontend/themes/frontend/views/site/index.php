<?php

use common\components\ContentHelper;
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

\Yii::$app->view->registerMetaTag(["test", null, null, array('property' => "og:image")]);

$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/css/font-icons/font-awesome/css/font-awesome.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/css/count_down/jquery.countdown.css');
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/count_down/jquery.plugin.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/frontend/themes/frontend/assets/js/count_down/jquery.countdown.js',['depends' => [\yii\web\JqueryAsset::className()]]);
	
$now = new DateTime();
	
/*$inlineScript = "$(function () {
	var austDay = new Date(".($registerDate * 1000).");
	var serverTime = '".$now->format("M j, Y H:i:s O")."';
	$('#count_down').countdown({until: austDay, serverSync: serverTime});
});";*/
$inlineScript = "$(function () {
	$('#count_down').countdown({until: new Date(2018, 7 - 1, 26)});
});";
$this->registerJs($inlineScript,  View::POS_END);
?>
<section class="contact-container index">
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
                        <div class="col-sm-12">
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
                        <div class="col-sm-12" align="center">
			
                                	<div id="count_down"></div>
		
                        </div>
                        <div class="col-sm-12">
                                <div class="main-content">
                                        <?= !empty($staticContent->content) ? $staticContent->content : ''; ?>
                                </div>
                        </div>
                </div>
        </div>
</section>

