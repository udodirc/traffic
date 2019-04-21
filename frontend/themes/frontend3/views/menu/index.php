<?php
use common\components\ContentHelper;

?>
<?= ($data !== null) ? ContentHelper::checkContentVeiables($data->content) : '' ?>
