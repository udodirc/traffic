<?php
use yii\helpers\Html;
use yii\web\View;
use common\models\StaticContent;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = (isset($ticketModel->subject)) ? $ticketModel->subject : '';
?>
<h2><?= Html::encode($this->title); ?></h2>
<br/>
<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<?php if (Yii::$app->session->hasFlash('success')): ?>
				<div class="alert alert-success" role="alert">
					<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
				</div>
				<?php elseif (Yii::$app->session->hasFlash('error')): ?>
				<div class="alert alert-danger" role="alert">
					<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
				</div>
				<?php endif; ?>
				<h4 class="card-title"><?= Yii::t('form', 'Тема').':&nbsp;'.Html::encode($this->title); ?></h4>
				<div class="ibox-content">
					<div class="panel-body">
					<?= ListView::widget([
						'dataProvider' => $dataProvider,
						'options' => [],
						'layout' => "{pager}\n{items}\n",
						'itemView' => function ($model, $key, $index, $widget) use ($ticketModel) {
							return $this->render('partial/_ticket_list_item',['model' => $model, 'ticketModel' => $ticketModel]);
						},
						'pager' => [
							'maxButtonCount' => 10,
						],
					]);
					?>
					</div>
				</div>
			</div>		
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<?php $form = ActiveForm::begin([
					'options' => [
						'class'=>'forms-sample',
					],
					'action'=>'send-message?id='.$ticketModel->id
					]); ?>
					<div class="form-group <?= isset($messageForm->errors['message']) ? 'has-danger' : ''?>">
						<?= Html::activeLabel($messageForm, 'message', [
							'label' => Yii::t('form', 'Сообщение').' ('.Yii::t('form', 'это поле должно быть заполнено').')'
						]); ?>
						<?= $form->field($messageForm, 'message')->widget(CKEditor::className(),[
							'editorOptions' => [
								'class' => 'form-control',
								'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
								'inline' => false, //по умолчанию false
								'language' => 'ru',
								'extraPlugins' => 'image',
								'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
							],
						])->label(false); ?>
						<?= Html::error($messageForm, 'message', [
							'class'=>'error mt-2 text-danger'
						]); ?>
					</div>
					<div class="form-group">
						<?= $form->field($messageForm, 'reCaptcha')->widget(
							common\widgets\captcha\ReCaptcha::className(),
							['siteKey' => '6Le3szsUAAAAAOMdQNGpbgKVumgxkm9cLBs5XPqP']
						); ?>
					</div>
					<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-primary mr-2']) ?>
				<?php ActiveForm::end(); ?>
			</div>		
		</div>
	</div>
</div>
