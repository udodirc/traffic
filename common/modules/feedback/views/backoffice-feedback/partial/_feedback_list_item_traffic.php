<?php
use yii\helpers\Html;
use yii\helpers\Url;

$id = (!is_null(\Yii::$app->user->identity)) ? \Yii::$app->user->identity->id : 0;
?>
<div class="timeline-item">
	<div class="row">
		<div class="col-xs-3 date">
			<i class="fa fa-comments"></i>
			<?= date("Y-m-d", $model->created_at); ?><br/>
        </div>
        <div class="col-xs-7 content">
			<div style="width:100%; overflow:hidden;">
				<strong><small class="text-navy"><?= Yii::t('form', 'Автор') ?></small>:&nbsp;<?= $model->getAutorName(); ?></strong>
				<span class="right margin-left-10">
				<?php if($id == $model->partner_id): ?>
					<?= Html::a('<i class="fa fa-file"></i>', ['update', 'id' => (!is_null($model)) ? $model->id : 0], ['alt' => Yii::t('form', 'Редактировать'), 'title' => Yii::t('form', 'Редактировать')]) ?>
					<?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => (!is_null($model)) ? $model->id : 0], ['alt' => Yii::t('form', 'Удалить'), 'title' => Yii::t('form', 'Удалить')]) ?>
				<?php endif;?>
				</span>
			</div>
            <p>
				<?= $model->feedback; ?>
			</p>
        </div>
	</div>
</div>
