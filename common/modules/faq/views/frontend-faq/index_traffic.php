<?php
use common\modules\faq\widgets\FaqWidget;
use yii\web\View;

$this->title = Yii::t('form', 'F.A.Q');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(Yii::$app->request->baseUrl.'/common/modules/faq/assets/js/faq.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<section id="contacts" class="container form-section">
	<div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="row">
						<h3 class="m-t-none m-b"><?= $this->title; ?></h3>
						<div class="pretext"> 
							<?= (isset($content) && $content != null) ? $content->content : ''; ?>
						</div>
						<?= FaqWidget::widget(['item_list' => $faqList, 'section_number' => 1]); ?>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>
