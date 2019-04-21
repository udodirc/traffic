<?php
use common\components\ContentHelper;

$this->title = $meta_title;
$this->registerMetaTag([
    'name' => 'description',
    'content' => $meta_desc,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $meta_keys,
]);
?>
<?= ($data !== null) ? ContentHelper::checkContentVeiables($data->content) : '' ?>
