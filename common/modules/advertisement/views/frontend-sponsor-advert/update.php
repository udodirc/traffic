<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\advertisement\models\SponsorAdvert */

$this->title = Yii::t('form', 'Спонсорская реклама');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Спонсорская реклама'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Update');
?>
<div class="sponsor-advert-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
		'url' => $url,
		'thumbnail' => $thumbnail,
		'id' => $id
    ]) ?>

</div>
