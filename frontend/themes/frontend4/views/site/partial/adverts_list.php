<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\AutoHelper;
?>
<?php if(!empty($advertsList)): ?>
<div class="adverts_list">
<div class="items">
	<?php foreach($advertsList as $i => $advertsData): ?>
	<div class="item">
		<div class="info left front">
			<div class="box">
				<div class="text_block">
					<div class="text">
						<?= Html::a($advertsData['title'], 'javascript:void(0)', ['onclick'=>'open_tab("'.$advertsData['link'].'")']) ?>
					</div>
					<div class="text">
						<?= $advertsData['text']; ?>
					</div>
				</div>
				<div class="actions">
					<span class="right"><?= Html::a(Yii::t('form','Проверить ссылку на вирусы'), 'http://online.us.drweb.com/result/?url='.$advertsData['link'], ['target'=>'_blank']) ?></span>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="clear"></div>
</div>
</div>
<?php endif; ?>
