<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="item">
	<? if(pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_BASENAME) != "reviews"): ?>
	<div class="item_actions" align="right">
		<?= Html::a(Yii::t('form','Редактировать'), Url::base().DIRECTORY_SEPARATOR.'text-advert/edit/'.$model->id, []) ?>
		&nbsp;/&nbsp;
		<?= Html::a(Yii::t('form','Удалить'), Url::base().DIRECTORY_SEPARATOR.'text-advert/delete/'.$model->id, []) ?>
	</div>
	<? endif; ?>
	<div class="info left">
		<div class="box">
			<div class="text">
				<?= Html::a($model->title, $model->link, ['target_'=>'blank']) ?>
			</div>
			<div class="text">
				<?= $model->text ?>
			</div>
		</div>
	</div>
</div>
