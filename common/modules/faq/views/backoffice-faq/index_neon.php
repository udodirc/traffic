<?php
use common\modules\faq\widgets\BackOfficeWidget;
use yii\web\View;
?>
<!-- Основная часть -->
<div class="col-sm-12 col-md-12">
	<div class="panel">
		<div class="panel-content">
        <!--GENERAL QUESTIONS-->
			<h4><b><?= Yii::t('form', 'Часто задаваемые вопросы'); ?></b></h4>
            <div class="panel-group faq-accordion" id="accordion_faq">
				<?= BackOfficeWidget::widget(['item_list' => $faqList]); ?>
			</div>
        </div>
    </div>
</div>
<!-- End Основная часть -->
