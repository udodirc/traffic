<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use common\models\StaticContent;
use yii\bootstrap\Modal;
use common\widgets\running_geo_data\RunningGeoDataWidget;
use common\widgets\counter\CounterWidget;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (!is_null($seo)) ? Html::encode($seo->meta_title) : '';
$this->registerMetaTag([
    'name' => 'description',
    'content' => (!is_null($seo)) ? HtmlPurifier::process($seo->meta_desc) : '',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => (!is_null($seo)) ? HtmlPurifier::process($seo->meta_keywords) : '',
]);

\Yii::$app->view->registerMetaTag(["test", null, null, array('property' => "og:image")]);

$this->params['breadcrumbs'][] = Html::encode($this->title);

$feedbackModel = (isset($this->params['feedbackModel'])) ? $this->params['feedbackModel'] : null;
?>
<section class="services section3" id="services" style="padding-top: 0px;">
    <div class="container">
        <div class="register_info">
            <div class="register_count">
                <div class="total_register bold"><?= Yii::t('menu', 'Регистраций сегодня'); ?></div>
                <div class="total_register"><?= (isset($this->params['curr_day_register']) && !empty($this->params['curr_day_register'])) ? $this->params['curr_day_register'] : 0; ?></div>
            </div>
            <div class="register_count">
                <div class="total_register bold"><?= Yii::t('menu', 'Всего партнеров'); ?></div>
                <div class="total_register"><?= (isset($this->params['total_register'])) ? $this->params['total_register'] : 0; ?></div>
            </div>
        </div>
        <div class="content_wrap register_widget">
            <div class="simple-marquee-container">
                <div class="marquee">
                    <?=
                    RunningGeoDataWidget::widget([
                        'item_list' => $partnersGeoDataList,
                        'options' => ['duration' => 10000, 'hover' => 'false']
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div style="padding: 20px 0 0 20px;">
            <?= isset($prelaunch) ? $prelaunch : ''; ?>
        </div>
<!--        <div class="container">-->
<!--            <div class="row">-->
<!--                <div class="col-md-8 col-md-offset-2">-->
<!--                    <button type="button" id="signup-link" class="btn standard-button">Регистрация</button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</section>
    <?php foreach ($contentList as $i => $content): ?>
    <section class="<?= isset($content->style) ? ($content->url . (isset($content->style) ? ' ' . Html::encode($content->style) : '')) : ''; ?>" id="<?= isset($content->url) ? Html::encode($content->url) : ''; ?>">
    <?= isset($content->content) ? HtmlPurifier::process($content->content) : ''; ?>
    </section>
<?php endforeach; ?>
<?php if ($feedbackModel !== null): ?>
    <section class="contact-us" id="contacts">
        <div class="container">	
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3 class="heading">Нужна помощь? Напишите нам!</h3>
                    <a href="<?= (isset(\Yii::$app->params['supportEmail'])) ? 'mailto:' . \Yii::$app->params['supportEmail'] : ''; ?>" class="contact-link expand-form2"><span class="icon_mail_alt"></span><?= (isset(\Yii::$app->params['supportEmail'])) ? \Yii::$app->params['supportEmail'] : ''; ?></a>
                    <!-- EXPANDED CONTACT FORM -->
                    <div class="expanded-contact-form" style="display:none">
                        <!-- FORM -->
                        <?php
                        $form = ActiveForm::begin([
                                    'options' => [
                                        'class' => 'contact-form',
                                        'id' => 'contact-form',
                                        'role' => 'form"',
                                    ],
                                    'action' => 'contacts',
                        ]);
                        ?>
                        <!-- IF MAIL SENT SUCCESSFULLY -->
                        <h6 class="success">
                            <span class="colored-text icon_check"></span>Ваше сообщение успешно отправлено. 
                        </h6>
                        <!-- IF MAIL SENDING UNSUCCESSFULL -->
                        <h6 class="error"></h6>
                        <div class="field-wrapper col-md-6">
                            <?= Html::activeTextInput($feedbackModel, 'name', ['id' => 'cf-name', 'class' => 'form-control input-box', 'placeholder' => Yii::t('form', 'Ваше имя')]); ?>
                        </div>
                        <div class="field-wrapper col-md-6">
    <?= Html::activeTextInput($feedbackModel, 'email', ['id' => 'cf-email', 'class' => 'form-control input-box', 'placeholder' => Yii::t('form', 'Email')]); ?>
                            <?= Html::error($feedbackModel, 'email', [
                            ]);
                            ?>
                        </div>
                        <div class="field-wrapper col-md-12">
                            <?= Html::activeTextArea($feedbackModel, 'text', ['id' => 'cf-message', 'rows' => '7', 'class' => 'form-control textarea-box', 'placeholder' => Yii::t('form', 'Ваше соообщение')]); ?>
                            <?= Html::error($feedbackModel, 'text', [
                            ]);
                            ?>
                        </div>
                        <div class="field-wrapper col-md-12">
                        <?=
                        $form->field($feedbackModel, 'reCaptcha')->widget(
                                common\widgets\captcha\ReCaptcha::className(),
                                ['siteKey' => '6LeiwJ8UAAAAADcw3ymj25xEht39C_nVMloTA84f']
                        );
                        ?>
                        </div>
    <?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn standard-button', 'id' => 'contacts-submit', 'data-style' => 'expand-left']) ?>
    <?php ActiveForm::end(); ?>
                        <!-- /END FORM -->
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
