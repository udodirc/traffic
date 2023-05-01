<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use common\components\Captcha;

/* @var $this yii\web\View */
/* @var $model common\modules\tickets\models\Tickets */
/* @var $form yii\widgets\ActiveForm */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= Html::encode($this->title); ?></h2>
<br/>
<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= Html::encode($this->title); ?></h4>
				<?php $form = ActiveForm::begin([
				'options' => [
					'class'=>'forms-sample',
				],
				]); ?>
					<div class="form-group <?= isset($model->errors['subject']) ? 'has-danger' : ''?>">
						<?= Html::activeLabel($model, 'subject', [
							'label' => Yii::t('form', 'Тема').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
						]); ?>
						<?= Html::activeTextInput($model, 'subject', ['class'=>'form-control form-control-danger']); ?>
						<?= Html::error($model, 'subject', [
							'class'=>'error mt-2 text-danger'
						]); ?>
					</div>
					<div class="form-group <?= isset($model->errors['text']) ? 'has-danger' : ''?>">
						<?= Html::activeLabel($model, 'text', [
							'label' => Yii::t('form', 'Текст сообщения').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
						]); ?>
						<?= $form->field($model, 'text')->widget(CKEditor::className(),[
							'editorOptions' => [
								'class' => 'form-control',
								'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
								'inline' => false, //по умолчанию false
								'language' => 'ru',
								'extraPlugins' => 'image',
								'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
							],
						])->label(false); ?>
						<?= Html::error($model, 'text', [
							'class'=>'error mt-2 text-danger'
						]); ?>
					</div>
                    <?php if(Captcha::isCaptchaAllowed('ticket')): ?>
                        <div class="form-group">
                            <?= $form->field($model, 'reCaptcha')->widget(
                                common\widgets\captcha\ReCaptcha::className(),
                                ['siteKey' => Yii::$app->params['captcha_site_key']]
                            ) ?>
                        </div>
                    <?php endif; ?>
					 <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => 'btn btn-primary mr-2']) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
