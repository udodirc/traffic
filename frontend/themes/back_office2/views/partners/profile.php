<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('form', 'Профиль');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Партнеры'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
    <h1><?= Html::encode(Yii::t('form', 'Профиль')) ?></h1>
    <p>
        <?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'login',
            'first_name',
            'last_name',
            'email:email',
            [
				'label'  => Yii::t('form', 'Статус'),
				'value'  => ($model->status > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен'),
			],
			[
				'attribute' => 'created_at', 
				'format' => ['date', 'php:Y-m-d H:i:s'],
				'filter'=>false,
			],
        ],
    ]) ?>
</div>
