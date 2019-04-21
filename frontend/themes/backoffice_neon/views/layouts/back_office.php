<?php
use frontend\assets\BackOfficeNeonAppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Menu as MenuWidget;
use common\models\Menu as FrontendMenu;

/* @var $this \yii\web\View */
/* @var $content string */

BackOfficeNeonAppAsset::register($this);
$bundle = BackOfficeNeonAppAsset::register($this);

$frontendMenu = new FrontendMenu();
$status = (isset($this->params['status'])) ? $this->params['status'] : 0;
$demoTotalAmount = (isset($this->params['demo_total_amount'])) ? $this->params['demo_total_amount'] : 0;
$totalAmount = (isset($this->params['total_amount'])) ? $this->params['total_amount'] : 0;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="<?= Yii::$app->charset ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" rel="stylesheet" href="#" id="skin_change"/>
		<title><?= Html::encode(Yii::t('menu', 'Личный кабинет')) ?></title>
		<?php $this->head() ?>
	</head>
	<body>
    <?php $this->beginBody() ?>
    <?php include_once("analyticstracking.php") ?>
    <?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
