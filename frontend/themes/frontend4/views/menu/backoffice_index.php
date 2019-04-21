<?php
use common\components\ContentHelper;
?>
<div class="partners-levels">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header bg-white">
					<h2><?= ($title !== null) ? $title : '' ?></h2>
				</div>
				<div class="card-block m-t-35">
					<?= ($data !== null) ? ContentHelper::checkContentVeiables($data->content) : '' ?>
				</div>
			</div>
		</div>
	</div>
</div>
