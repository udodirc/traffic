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

\Yii::$app->view->registerMetaTag(["test", null, null, array('property' => "og:image")]);

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container center767">
	<div class="row offs">
		<?= ($staticContent !== null) ? $staticContent->content : ''; ?>
	</div>
</div>
