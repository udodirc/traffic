<?php
use yii\helpers\Html;
	
\yii\bootstrap\Modal::begin([
	'headerOptions' => ['id' => 'modal-message-header'],
	'id' => 'modal-message',
	'size' => 'modal-lg',
	//keeps from closing modal with esc key or by clicking out of the modal.
	// user must click cancel or X to close
	//'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
?>
<div id='modal-message-text'>
	<?= ($modalContent !== null) ? $modalContent->content : ''; ?>
</div>
<?php \yii\bootstrap\Modal::end(); ?>
