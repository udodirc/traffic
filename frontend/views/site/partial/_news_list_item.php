<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<li>
	<div class="block">
		<div class="block_content">
			<h2 class="title">
				<?= Html::a($model->title, \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?>
			</h2>
            <div class="byline">
				<span><?= date("Y-m-d", $model->created_at); ?></span>
            </div>
            <p class="excerpt">
				<?= $model->short_text; ?><?= Html::a(Yii::t('form', 'Подробнее'), \Yii::$app->request->BaseUrl.'/news/'.$model->id, []); ?>
            </p>
		</div>
	</div>
</li>
