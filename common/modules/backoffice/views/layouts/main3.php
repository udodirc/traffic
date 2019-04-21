<?php
//use Yii;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\widgets\BottomMenuWidget;
use common\widgets\RightMenuWidget;
use common\models\Menu as FrontendMenu;
use common\modules\backoffice\models\forms\LoginForm;
use common\modules\backoffice\models\forms\SignupForm;
use yii\helpers\ArrayHelper;
use common\modules\backoffice\models\Partners;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$bundle = AppAsset::register($this);

$frontendMenu = new FrontendMenu();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<!-- Переключение IE в последнию версию, на случай если в настройках пользователя стоит меньшая -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<!-- Адаптирование страницы для мобильных устройств -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Запрет распознования номера телефона -->
	<meta name="format-detection" content="telephone=no">
	<meta name="SKYPE_TOOLBAR" content ="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
	
	<!-- Заголовок страницы -->
	 <title><?= Html::encode($this->title) ?></title>
	
	<!-- Традиционная иконка сайта, размер 16x16, прозрачность поддерживается. Рекомендуемый формат: .ico или .png -->
	<link rel="shortcut icon" href="favicon.ico">

	<!-- Иконка сайта для IPad от Apple, рекомендуемый размер 114x114, прозрачность не поддерживается -->
	<link rel="apple-touch-icon" href="apple-touch-icon-ipad.png">
	
	<!-- Иконка сайта для Iphone от Apple, рекомендуемый размер 72x72, прозрачность не поддерживается -->
	<link rel="apple-touch-icon" href="apple-touch-icon-iphone.png">
	
    <?php $this->head() ?>
    <!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold|PT+Sans+Narrow:regular,bold|Droid+Serif:iamp;v1' rel='stylesheet' type='text/css' />
</head>
<body>
    <?php $this->beginBody() ?>
    <!-- Шапка -->
	<header>
	<?php include_once("analyticstracking.php") ?>
	<div class="cont">
		<div class="logo left"><a href="<?=\Yii::$app->request->BaseUrl; ?>"><?= Html::img(\Yii::getAlias('@web').DIRECTORY_SEPARATOR.Url::to('@frontend_images'.DIRECTORY_SEPARATOR.'logo.png'), ['alt'=>'']) ?></a></div>
		<div class="account right">
			<?php if(Yii::$app->user->identity !== null): ?>
				<a href="<?=\Yii::$app->request->BaseUrl; ?>/partners/profile/<?= \Yii::$app->user->identity->id ?>" class="profile_link"><?= \Yii::$app->user->identity->login ?></a>
				<a href="<?=\Yii::$app->request->BaseUrl; ?>/logout" class="logout_link"></a>
			<?php else: ?>
				<a href="<?=\Yii::$app->request->BaseUrl; ?>/login" class="login_link"><span>Личный кабинет</span></a>
			<?php endif; ?>
		</div>
		<div class="popup signup_popup">
			<div class="close_popup close_signup_popup" id="close_signup_popup"></div>
		</div>
		<div class="signup right">
			<?php if(Yii::$app->user->identity == null): ?>
			<a href="<?=\Yii::$app->request->BaseUrl; ?>/signup">Регистрация</a>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</div>
	</header>
	<!-- End Шапка -->
	<!-- Основная часть -->
	<section class="page_head">
		<div class="cont">
			<?= BottomMenuWidget::widget(['item_list' => FrontendMenu::getMenuList(2)]); ?>
		</div>
	</section>
	<div class="cont">
		<?= $content ?>
		<!-- End Основная часть -->
		<div class="clear"></div>
	</div>
	<!-- Подвал -->
	<footer>
		<div class="counter">
			<!--LiveInternet counter-->
			<script type="text/javascript"><!--
			document.write("<a href='//www.liveinternet.ru/click' "+
			"target=_blank><img src='//counter.yadro.ru/hit?t45.1;r"+
			escape(document.referrer)+((typeof(screen)=="undefined")?"":
			";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
			screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
			";"+Math.random()+
			"' alt='' title='LiveInternet' "+
			"border='0' width='31' height='31'><\/a>")
			//-->
			</script><!--/LiveInternet-->
		</div>
		<div class="contacts">
			<?= (isset($this->params['contacts'])) ? $this->params['contacts']['content'] : ''; ?>
		</div>
		<div class="cont footer">	
			<div class="links">
				<?= BottomMenuWidget::widget(['item_list' => FrontendMenu::getMenuList(2)]); ?>
			</div>
			<div class="quiestion">
				<div>Если у Вас возникли вопросы или предложения напишите нам</div>
				<div class="link"><a class="feedback" href="#">Написать нам</a></div>
			</div>		
			<div class="copyright">&copy; 2016 spillovermoney.com "СпиловерМани - Перелив Денег".  Все права защищены</div>
		</div>
	</footer>
	<?php if(isset($this->params['feedbackModel']) && $this->params['feedbackModel'] !== null): ?>
		<?=$this->render('partial/feedback_form', [
			'model' => $this->params['feedbackModel']
		]);?>
	<?php endif; ?>
	<div class="overlay"></div>
	<!-- End Подвал -->
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
