<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;
use common\widgets\running_geo_data\RunningGeoDataWidget;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (!is_null($seo)) ? $seo->meta_title : '';
$this->registerMetaTag([
    'name' => 'description',
    'content' => (!is_null($seo)) ? $seo->meta_desc : '',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => (!is_null($seo)) ? $seo->meta_keywords : '',
]);

\Yii::$app->view->registerMetaTag(["test", null, null, array('property' => "og:image")]);

$this->params['breadcrumbs'][] = $this->title;

$feedbackModel = (isset($this->params['feedbackModel'])) ? $this->params['feedbackModel'] : null;
?>
<?php foreach($contentList as $i => $content): ?>
<section class="<?= isset($content->style) ? ($content->url.(isset($content->style) ? ' '.$content->style : '')) : ''; ?>" id="<?= isset($content->url) ? $content->url : ''; ?>">
	<?= isset($content->content) ? $content->content : ''; ?>
</section>
<?php endforeach; ?>
<?php if($feedbackModel !== null): ?>
<section class="contact-us" id="contacts">
	<div class="container">	
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<h3 class="heading">Need any help? Contact us now!</h3>
				<a href="" class="contact-link expand-form"><span class="icon_mail_alt"></span>Contact us now</a>
				<!-- EXPANDED CONTACT FORM -->
				<div class="expanded-contact-form">
					<!-- FORM -->
					<?php $form = ActiveForm::begin([
					'options' => [
						'class'=>'contact-form',
						'id'=>'contact-form',
						'role'=>'form"',
					],
					'action'=>'contacts',
					]); 
					?>
						<!-- IF MAIL SENT SUCCESSFULLY -->
						<h6 class="success">
							<span class="colored-text icon_check"></span> Your message has been sent successfully. 
						</h6>
						<!-- IF MAIL SENDING UNSUCCESSFULL -->
						<h6 class="error"></h6>
						<div class="field-wrapper col-md-6">
							<?= Html::activeTextInput($feedbackModel, 'name', ['id'=>'cf-name', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Ваше имя')]); ?>
						</div>
						<div class="field-wrapper col-md-6">
							<?= Html::activeTextInput($feedbackModel, 'email', ['id'=>'cf-email', 'class'=>'form-control input-box', 'placeholder'=>Yii::t('form', 'Email')]); ?>
							<?= Html::error($feedbackModel, 'email', [
							]); ?>
						</div>
						<div class="field-wrapper col-md-12">
							<?= Html::activeTextArea($feedbackModel, 'text', ['id'=>'cf-message', 'rows'=>'7', 'class'=>'form-control textarea-box', 'placeholder'=>Yii::t('form', 'Ваше соообщение')]); ?>
							<?= Html::error($feedbackModel, 'text', [
							]); ?>
						</div>
						<div class="field-wrapper col-md-12">
							<?= $form->field($feedbackModel, 'reCaptcha')->widget(
								common\widgets\captcha\ReCaptcha::className(),
								['siteKey' => '6LeiwJ8UAAAAADcw3ymj25xEht39C_nVMloTA84f']
							); ?>
						</div>
						<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn standard-button', 'id'=>'contacts-submit', 'data-style'=>'expand-left']) ?>
					<?php ActiveForm::end(); ?>
					<!-- /END FORM -->
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
