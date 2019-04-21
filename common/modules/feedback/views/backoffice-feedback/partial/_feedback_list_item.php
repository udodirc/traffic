<?php
use yii\helpers\Html;
use yii\helpers\Url;

$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
?>
<div class="timeline-box">
	<div class="timeline-icon bg-primary">
		<i class="fa fa-file"></i>
	</div>
    <div class="timeline-content">
		<?= $model->feedback; ?>
    </div>
    <div class="timeline-footer">
		<span class="left margin-left-10"><?= Yii::t('form', 'Автор').':&nbsp;&nbsp;'.$model->getAutorName(); ?>&nbsp; - &nbsp;</span>
		<span class="left"><?= date("Y-m-d", $model->created_at); ?></span>
		<?php if($id == $model->partner_id): ?>
			<span class="right margin-left-10">
				<?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => (!is_null($model)) ? $model->id : 0], ['alt' => Yii::t('form', 'Удалить'), 'title' => Yii::t('form', 'Удалить')]) ?>
			</span>
			<span class="right margin-left-10">
				<?= Html::a('<i class="fa fa-file"></i>', ['update', 'id' => (!is_null($model)) ? $model->id : 0], ['alt' => Yii::t('form', 'Редактировать'), 'title' => Yii::t('form', 'Редактировать')]) ?>
			</span>
		<?php endif;?>
	</div>
</div>
