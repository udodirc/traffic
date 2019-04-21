<?php
use common\modules\faq\widgets\BackOfficeWidget;
use yii\web\View;
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Часто задаваемые вопросы'); ?></h5>
			</div>
			<div class="ibox-content">
				<?= BackOfficeWidget::widget(['item_list' => $faqList]); ?>
			</div>
		</div>
	</div>
</div>
