<?php
use frontend\assets\FrontendAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;

/* @var $this \yii\web\View */
/* @var $content string */

FrontendAppAsset::register($this);
$bundle = FrontendAppAsset::register($this);
$brandSlogan = (isset($this->params['brand_slogan'])) ? $this->params['brand_slogan'] : '';
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
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		<title><?= $this->title; ?></title>
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
		<?php include_once("analyticstracking.php") ?>
		<div id="wrapper">
            <div class="wrapper-inner">			
                <!-- top -->
				<div id="top">
					<div class="container">
						<div class="row">
							<!-- Logo -->
							<div id="logo" class="col-sm-4">
								<a href="/">
									<?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'logo.png'), ['alt'=>'logo', '']) ?>
									<span class="site-logo">
										<?= $brandSlogan; ?>
									</span>
								</a>
							</div>
							<nav id="icemegamenu" class="moduletable home_menu col-sm-8" style="position: inherit;">
								<div class="module_container">
									<div class="icemegamenu">
										<!-- #menu -->
										<?= MenuWidget::widget([
											'items' => FrontendMenu::getMenuList(2),
											'options' => [
												'id'=>"icemegamenu",
											],
											'submenuTemplate' => '<ul class="icesubMenu icemodules sub_level_1" style="width:188px">{items}</ul>',
										]);
										?>
										<!-- /#menu -->
									</div>
								</div>
							</nav>
						</div>
					</div>
				</div>
				<!-- #content -->
				<?= $content ?>
			</div>
		</div>
		<!-- #footer -->
		<div id="footer-wrapper">
			<div class="footer-wrapper-inner">
				<!-- Copyright -->
				<div id="copyright" role="contentinfo">
                    <div class="container">                        
						<div class="row">
                           <div class="copyright col-sm-">
								<span class="siteName"><?= Yii::$app->name; ?></span>
                                <span class="copy">&copy; </span><span class="year"><?= date("Y"); ?></span>
                           </div>
						</div>
					</div>
				</div>
            </div>
        </div>
        <div id="back-top">
			<a href="#"><span></span></a>
		</div>  
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
