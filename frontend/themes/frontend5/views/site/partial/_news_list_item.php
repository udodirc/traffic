<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<li>
	<div class="block">
		<div class="block_content">
			<h4 class="title">
				<?= Html::a($model->title, \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?>
			</h4>
            <div class="byline">
				<span><?= date("Y-m-d", $model->created_at); ?></span>
            </div>
            <p class="excerpt">
				<?= $model->short_text; ?><?= Html::a(Yii::t('form', 'Подробнее'), \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?>
            </p>
		</div>
	</div>
	<hr>
</li>
