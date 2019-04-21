<?php
use common\modules\faq\widgets\BackOfficeWidget;
use yii\web\View;
?>
<!-- Основная часть -->
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="faq-section">
					<div class="container-fluid bg-success py-2">
						<p class="mb-0 text-white"><?= Yii::t('form', 'Часто задаваемые вопросы'); ?></p>
                    </div>
                    <div id="accordion-1" class="accordion">
						<?= BackOfficeWidget::widget([
							'item_list' => $faqList,
							'theme' => $theme
						]); ?>
                    </div>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- End Основная часть -->
