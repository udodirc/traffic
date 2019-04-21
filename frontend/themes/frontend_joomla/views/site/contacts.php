<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Контакты');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="content-inner">
		<!-- Left sidebar -->
		<div id="component" class="col-sm-12" style="overflow:hidden;">
			<main role="main">
				<div id="system-message-container"></div>
					<div class="page page-contact page-contact__">
						<!-- CONTACT FORM -->
						<div class="row">
							<!-- Address -->
							<div class="contact_details col-sm-4">
								<?= ($address !== null) ? $address->content : '' ?>
							</div>
							<div class="col-sm-8">
								<div class="contact_misc visible-first">
									<h3 class="heading-style-3 visible-first">
								</div>
								<div id="contacts">
									<fieldset>
										<div class="row">
										<?php $form = ActiveForm::begin([
												'options' => [
													'class' => 'mod_tm_ajax_contact_form', 
												],
												'action'=>'send-feedback',
											]); ?>
											<?= $form->field($model, 'name', [
												'template' => '<div class="control control-group-input col-sm-6">
													<div class="control">
														{input}{hint}{error}
													</div>
												</div>',
												'inputOptions' => [
													'placeholder' => Yii::t('form', 'Имя'),
													'class' => '"mod_tm_ajax_contact_form_text hasTooltip',
												]]); 
											?>
											<?= $form->field($model, 'email', [
												'template' => '<div class="control control-group-input col-sm-6">
													<div class="control">
														{input}{hint}{error}
													</div>
												</div>',
												'inputOptions' => [
													'placeholder' => Yii::t('form', 'Email'),
													'class' => '"mod_tm_ajax_contact_form_text hasTooltip',
												]]); 
											?>
											<?= $form->field($model, 'text', [
												'template' => '<div class="control control-group-input col-sm-12">
													<div class="control">
														{input}{hint}{error}
													</div>
												</div>',
												'inputOptions' => [
													'placeholder' => Yii::t('form', 'Сообщение'),
													'class' => '"mod_tm_ajax_contact_form_text hasTooltip',
												]])->textArea(['rows' => '6', 'id' => 'message_text']);
											?>
											<div class="control control-group-button col-sm-12">
												<div class="control">
													<?/*= $form->field($model, 'reCaptcha')->widget(
														common\widgets\captcha\ReCaptcha::className(),
														['siteKey' => '6LdZ8ysUAAAAAMEPRNT1QtweF7aDF54VbViOq1lz']
													);*/?>
												</div>
											</div>
											<div class="control control-group-button col-sm-12">
												<div class="control">
													<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-primary mod_tm_ajax_contact_form_btn']) ?>
												</div>
											</div>
										<?php ActiveForm::end(); ?>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			 </main>
		</div>
    </div>
</div>
