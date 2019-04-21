<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<article class="item column-<?= ($model->id % 2 == 0) ? 2 : 1; ?>" id="<?= $model->id; ?>">
	<!--  title/author -->
	<header class="item_header">
		<h5 class="timeline">
			<?= Yii::t('form', 'Автор').':'.$model->getAutorName(); ?>
		</h5>
	</header>
	<!-- info TOP -->
	<div class="item_info muted">
		<dl class="item_info_dl">
			<dt class="article-info-term"></dt>
			<dd>
				<time datetime="2001-06-22 20:25" class="item_published">
					<?= date("Y-m-d", $model->created_at); ?>			
				</time>
			</dd>
		</dl>
	</div>
	<!-- Introtext -->
	<div class="item_introtext">
		<p><?= $model->feedback; ?></p>
	</div>
</article><!-- end item -->
