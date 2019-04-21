<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\feedback\models\Feedback */

$this->title = Yii::t('form', 'Создать отзыв');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Feedbacks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
