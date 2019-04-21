<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
$sponsorData = (isset($sponsorData)) ? $sponsorData : null;
/*
$this->registerJsFile(Yii::$app->request->baseUrl.'/common/modules/backoffice/assets/js/intlTelInput.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/common/modules/backoffice/assets/css/intlTelInput.css');

$inlineScript = '$("#phone").intlTelInput({
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: "body",
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      // hiddenInput: "full_number",
      // initialCountry: "auto",
      // nationalMode: false,
      // onlyCountries: ["us", "gb", "ch", "ca", "do"],
      // placeholderNumberType: "MOBILE",
      // preferredCountries: ["cn", "jp"],
      // separateDialCode: true,
      utilsScript: "'.Yii::$app->request->baseUrl.'/common/modules/backoffice/assets/js/utils.js"
});';
$this->registerJs($inlineScript,  View::POS_END);
/*$inlineScript = 'var telInput = $("#phone"),
  errorMsg = $(".help-block help-block-error"),
  validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({
  utilsScript: "../../build/js/utils.js"
});

var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
		$("#phone_err").html("'.Yii::t('form', 'Введите правильный номер телефона').'");
      telInput.addClass("error");
    }
  }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);';
$this->registerJs($inlineScript,  View::POS_END);*/
?>
<section id="contacts" class="container form-section">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="alert alert-success">
					<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
				</div>
			</div>
		</div>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="alert alert-danger">
					<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- /.flash-error -->
	<?php endif; ?>	
    <div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-12"><h3 class="m-t-none m-b"><?= $this->title; ?></h3>
							<?php $form = ActiveForm::begin([
								'options' => [
									'role' => 'form', 
								]
							]); ?>
								<?= $form->field($model, 'login', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Логин'),
										'class' => 'form-control',
									]]); 
								?>
								<?= $form->field($model, 'first_name', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Имя'),
										'class' => 'form-control',
									]]); 
								?>
								<?= $form->field($model, 'last_name', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Фамилия'),
										'class' => 'form-control',
									]]); 
									?>
								<?= $form->field($model, 'email', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Email'),
										'class' => 'form-control',
									]]); 
								?>
								<?= $form->field($model, 'password', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Пароль'),
										'class' => 'form-control',
									]])->passwordInput(['maxlength' => 32]);
								?>
								<?= $form->field($model, 're_password', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'placeholder' => Yii::t('form', 'Повторить пароль'),
										'class' => 'form-control',
									]])->passwordInput(['maxlength' => 32]);
								?>
								<?/*= $form->field($model, 'phone', [
									'template' => '<div class="form-group">
										{input}{hint}{error}
									</div>',
									'inputOptions' => [
										'class' => 'form-control',
										'id' => 'phone',
										'type' => 'tel',
									]]); 
								*/?>
								<div class="col-sm-12">
									<div class="form-group">
										<?=$form->field($model, 'rules_agree')->checkbox([
											'inline' => true,
											'enableLabel' => false,
											'checked' => false,
											'template' => '
											{input}
											<a href="'.\Yii::$app->request->BaseUrl.'/rules_agree" class="rules_agree">'.Yii::t('form', 'С правилами сайта согласен').'</a>
											{error}',
										]);?>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<?= $form->field($model, 'reCaptcha')->widget(
											common\widgets\captcha\ReCaptcha::className(),
											['siteKey' => '6LdQjRMUAAAAAECdoEwiGZwBKmLeMGMmZHNMBdRf']
										);?>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<?= Html::submitButton(Yii::t('form', 'Регистрация'), ['class' => 'btn btn-sm btn-primary pull-right m-t-n-xs']) ?>
									</div>
								</div>
							<?php ActiveForm::end(); ?>
                        </div>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>
