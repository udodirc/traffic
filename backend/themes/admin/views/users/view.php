<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('form', 'Профиль');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Пользователи'), 'url' => ['index']];
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
        <?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model->id, 'profile' => $profile], ['class' => 'btn btn-success']) ?>
        <?php
			if(!$profile)
			{
				echo Html::a(Yii::t('form', 'Удалить'), ['delete', 'id' => $model->id], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => Yii::t('form', 'Вы на самом деле хотите удалить эту запись?'),
						'method' => 'post',
					],
				]);
			}
         ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
				'label'  => Yii::t('form', 'Статус'),
				'value'  => ($model->status > 0) ? Yii::t('form', 'Активен') : Yii::t('form', 'Не активен'),
			],
			[
				'label'  => Yii::t('form', 'Группа'),
				'value'  => ($model->groups->name !== '') ? $model->groups->name : Yii::t('form', 'Нет группы'),
			],
            [
				'attribute' => 'created_at', 
				'format' => ['date', 'php:Y-m-d H:i:s'],
				'filter'=>false,
			],
        ],
    ]) ?>
</div>
