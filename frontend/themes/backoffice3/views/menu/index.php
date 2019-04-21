<?php
use common\components\ContentHelper;
?>
<!-- page content -->
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?= ($data !== null) ? $data->title : '' ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
				<?= ($data !== null) ? ContentHelper::checkContentVeiables($data->content) : '' ?>
            </div>
        </div>
   </div>
</div>
<!-- /page content -->





