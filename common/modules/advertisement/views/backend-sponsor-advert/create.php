<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\feedback\models\Feedback */

$this->title = Yii::t('form', 'Создать');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Спонсорская реклама'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
		'category' => $category,
		'url' => $url,
		'thumbnail' => $thumbnail,
		'id' => 0
    ]) ?>
</div>
