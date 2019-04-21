<?php
use frontend\assets\FrontendAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;
use common\modules\seo\widgets\CounterWidget;
use common\widgets\BottomMenuWidget;

/* @var $this \yii\web\View */
/* @var $content string */

FrontendAppAsset::register($this);
$bundle = FrontendAppAsset::register($this);
$brandSlogan = (isset($this->params['brand_slogan'])) ? $this->params['brand_slogan'] : '';
$phone = (isset($this->params['phone'])) ? $this->params['phone'] : '';
$location = (isset($this->params['location'])) ? $this->params['location'] : '';
$breadCrumbs = (isset($this->params['bread_crumbs'])) ? $this->params['bread_crumbs'] : '';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
	<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
	<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
	<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="<?= Yii::$app->charset ?>">
		 <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="format-detection" content="telephone=no"/>
		<link rel="icon" href="<?= \Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'favicon.ico'); ?>" type="image/x-icon">
		<title><?= $this->title; ?></title>
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
		<?php include_once("analyticstracking.php") ?>
		<div id="wrapper">
            <div class="wrapper-inner">
				<div id="top" class="stuck_position">
					<div id="logo" class="">
						<a href="/">
							<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'logo.png'), ['alt'=>'logo']) ?>
						</a>
					</div>
					<div class="moduletable top_address ">
						<div class="module_container">
							<div class="mod-newsflash-adv mod-newsflash-adv__top_address cols-2">
								<div class="row">
									<article class="col-sm-6 item item_num0 item__module   visible-first">
										<div class="item_content">
											<i class="fa fa-phone"></i>
												<!-- Item title -->
												<!-- Introtext -->
												<div class="item_introtext">
													<?= $phone; ?>	
												</div>
												<!-- Read More link -->
										</div>
										<div class="clearfix"></div>  
									</article>
									<article class="col-sm-6 item item_num1 item__module  lastItem visible-first">
										<div class="item_content">
											<i class="fa fa-map-marker"></i>
											<!-- Item title -->
											<!-- Introtext -->
											<div class="item_introtext">
												<?= $location; ?>	
											</div>
											<!-- Read More link -->
										</div>
										<div class="clearfix"></div>  
									</article>
								</div> 
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div id="navigation" class="stuck_position stuck" style="z-index: 1000; top: auto; position: static; width: 100%;">
					<nav class="moduletable home_menu">
						<div class="module_container">
							<div class="icemegamenu">
								<!-- #menu -->
								<?= MenuWidget::widget([
									'items' => FrontendMenu::getMenuList(2),
									'options' => [
										'id'=>'icemegamenu',
									],
									'submenuTemplate' => '<ul class="icesubMenu icemodules sub_level_1" style="width:188px">{items}</ul>',
								]);
								?>
								<!-- /#menu -->
							</div>
						</div>
					</nav>
				</div>
				<div id="breadcrumbs" class="stuck_position">
					<div class="moduletable">
						<div class="module_container">
							<?= Breadcrumbs::widget(['links' => $breadCrumbs]); ?>
						</div>
					</div>
				</div>
				<div id="content">
					<div class="container">
						<?= $content ?>
					</div>
				</div>
			</div>
		</div>
		<div id="footer-wrapper">
            <div class="footer-wrapper-inner">
				<!-- Copyright -->
                <div id="copyright" role="contentinfo">
                    <div class="container">                        
						<div class="row">
                            <div class="copyright col-sm-12">
								<span class="siteName"><?= Yii::$app->name; ?></span>
                                <span class="copy">© </span><span class="year">2017</span>
							</div>
                           <!-- {%FOOTER_LINK} -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
