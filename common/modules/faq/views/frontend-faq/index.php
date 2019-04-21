<?php
use common\modules\faq\widgets\FaqWidget;
use yii\web\View;

$this->registerJsFile(Yii::$app->request->baseUrl.'/common/modules/faq/assets/js/faq.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = (isset($this->params['meta_title'])) ? $this->params['meta_title'] : 'F.A.Q. - Withoutadmin-"Всемирная касса взаимопомощи!"';
$this->registerMetaTag([
    'name' => 'description',
    'content' => (isset($this->params['meta_tags'])) ? $this->params['meta_tags'] : 'F.A.Q.|Всемирная касса взаимопомощи! ОТКРЫТИЕ НОВОГО ПРОЕКТА "БЕЗ АДМИНА"',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => (isset($this->params['meta_keywords'])) ? $this->params['meta_keywords'] : 'f.a.q., чаво, без админа, withoutadmin, заработок в сети, заработок в интернете, новый проект, мгновенные денежные переводы, матрица, млм, mlm',
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="moduletable col-sm-12">
				<div class="module_container">
					<ul class="breadcrumb">
						<li class="active"><span><?= Yii::t('form', 'F.A.Q'); ?></span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="content">
	<div class="container">
		<div class="row">
			<div class="content-inner">
				<div id="component" class="col-sm-12">
					<main role="main">
						<div id="content-bottom">
							<div class="row">
								<div class="moduletable col-sm-12">
									<div class="module_container">
										<header class="page_header">
											<h2 class="moduleTitle heading-style-2 visible-first">
												<span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first"><?= Yii::t('menu', 'Часто задаваемые вопросы'); ?></span>
											</h2>
										</header>
										<div class="mod-bootstrap-collapse mod-bootstrap-collapse__">
											<div class="pretext"> 
												<?= (isset($content) && $content != null) ? $content->content : ''; ?>
											</div>
											<?= FaqWidget::widget(['item_list' => $faqList, 'section_number' => 1]); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</main>
				</div>
			</div>
		</div>
	</div>
</div>
