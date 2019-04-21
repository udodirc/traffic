<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('menu', 'Правила пользования сайта');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div id="component" class="col-sm-12">
		<main role="main">
			<div id="system-message-container"></div>
				<div class="page page-contact page-contact__">
					<div class="row">
						<div class="col-sm-12">
							<?= ($content !== null) ? $content->content : ''?>
						</div>
					</div>
				</div>
			 </main>
		</div>
    </div>
</div>
