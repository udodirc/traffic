<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\Faq */

$this->title = Yii::t('form', 'Создать Faq');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Faq'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'typesList' => $typesList,
        'update' => false,
    ]) ?>
</div>
