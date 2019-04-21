<?php
use yii\helpers\Html;
use common\components\ContentHelper;

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
?>
<!-- page content -->
<div class="col-sm-12 col-md-12">
	<div class="panel">
		<div class="panel-header">
			<h4 class="list-title"><?= Html::encode($this->title); ?></h4>
		</div>
        <div class="panel-content">
        <?= ($data !== null) ? ContentHelper::checkContentVeiables($data->content) : '' ?>             
		</div>
	</div>
</div>
<!-- /page content -->
