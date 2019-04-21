<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Service;

/* @var $this yii\web\View */
/* @var $model app\models\Permissions */

$this->title = ($model->groups->name !== '') ? $model->groups->name : Yii::t('form', 'Нет группы');
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-view">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('form', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('form', 'Вы на самом деле хотите удалить эту запись?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
				'label'  => Yii::t('form', 'Группа'),
				'value'  => ($model->groups->name !== '') ? $model->groups->name : Yii::t('form', 'Нет группы'),
			],
            [
				'label'  => Yii::t('form', 'Контроллер'),
				'value'  => Service::getControllerNameByID($model->controller_id),
			],
			[
				'label'  => Yii::t('form', 'Просмотр'),
				'value'  => ($permisions_list['view_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет'),
			],
            [
				'label'  => Yii::t('form', 'Создание'),
				'value'  => ($permisions_list['create_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет'),
			],
			[
				'label'  => Yii::t('form', 'Редактирование'),
				'value'  => ($permisions_list['update_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет'),
			],
			[
				'label'  => Yii::t('form', 'Удаление'),
				'value'  => ($permisions_list['delete_perm'] > 0) ? Yii::t('form', 'Разрешенно') : Yii::t('form', 'Запрет'),
			],
        ],
    ]) ?>

</div>
