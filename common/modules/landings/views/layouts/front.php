<?php
use frontend\assets\LandingAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

LandingAppAsset::register($this);
$bundle = LandingAppAsset::register($this);
$bundle->baseUrl = (isset($this->params['source_path'])) ? $this->params['source_path'] : '';
$bundle->sourcePath = (isset($this->params['source_path'])) ? $this->params['source_path'] : '';
$bundle->css = (isset($this->params['styles']) && (!empty($this->params['styles']))) ? $this->params['styles'] : [];
$bundle->js = (isset($this->params['js']) && (!empty($this->params['styles']))) ? $this->params['js'] : [];
$innerStyle = (isset($this->params['inner_style'])) ? $this->params['inner_style'] : '';
$innerJs = (isset($this->params['inner_js'])) ? $this->params['inner_js'] : '';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Fonts -->
	<!--<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold|PT+Sans+Narrow:regular,bold|Droid+Serif:iamp;v1' rel='stylesheet' type='text/css' />	-->
</head>
<body>
    <?php $this->beginBody() ?>
    <?php if ($innerStyle !== ''): ?>
		<style>
		<?= $innerStyle ?>
		</style>
	<?php endif; ?>	
	<?php if ($innerJs !== ''): ?>
		<script>
		<?= $innerJs ?>
		</script>
	<?php endif; ?>	
	<?= $content ?>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
