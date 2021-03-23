<?php
use yii\helpers\Html;
use yii\web\View;

$inlineScript = "$(function () {
	document.getElementById('paybox_form').submit();
});";
$this->registerJs($inlineScript,  View::POS_END);
?>
<form method="post" id="paybox_form" name="paybox_form" action="https://payeer.com/merchant/">
	<?php foreach($params as $strName => $strValue): ?>
		<input type="hidden" name="<?= $strName; ?>" value="<?= $strValue; ?>">
	<?php endforeach; ?>
	<input type="submit" value="Pay">
</form>
