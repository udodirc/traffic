<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php \yii\bootstrap\Modal::begin([
		'header' => '<h2>'.Yii::t('form', 'Обратная связь').'</h2>',
		'headerOptions' => [],
		'id' => 'modal-message',
		'size' => 'modal-lg',
		//keeps from closing modal with esc key or by clicking out of the modal.
		// user must click cancel or X to close
		//'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
	]);
?>
	<div id='modal-message-text'>
	<?php $form = ActiveForm::begin(['id' => 'modal-message-form', 'fieldConfig' => [], 'action'=>'send-feedback']); ?>
		<div class="feedback_field">
			<?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
		</div>
		<div class="feedback_field">
			<?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>
		</div>
		<div class="feedback_field">
			<?= $form->field($model, 'text')->textArea(['rows' => '6', 'id' => 'message_text'])->label(true) ?>
		</div>
		<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-success', 'id' => 'send_message']) ?>
	<?php ActiveForm::end(); ?>
	</div>
<?php \yii\bootstrap\Modal::end(); ?>
