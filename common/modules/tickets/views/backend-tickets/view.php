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
<div class="ticket">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="alert alert-success fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="alert alert-danger fade in">
		<a href="#" class="close" data-dismiss="alert">×</a>
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<div class="animated fadeInUp">
		<div class="col-sm-12">
			<div class="panel">
				<div class="panel-header">
					<span class="ticket-header green"><?= Yii::t('form', 'Тема').':&nbsp;'.$this->title; ?></span>
				</div>
			</div>
		</div>
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
		<div class="row message">
			<div class="col-sm-12">
				<div class="panel">
					<div class="panel-header">
						<span class=""><?= Yii::t('form', 'Сообщение'); ?></span>
					</div>
					<div class="panel-content">
						<?php $form = ActiveForm::begin([
							'id'=>'inline-validation',
							'options' => [
								'class'=>'form-horizontal form-stripe'
							],
							'action'=>'send-message/'.$ticketModel->id
						]); ?>
							<div class="form-group">
								<?= Html::activeLabel($messageForm, 'text', [
									'class'=>'col-sm-3 control-label',
									'label' => Yii::t('form', 'Сообщение')
								]); ?>
								<div class="col-sm-9">
									<?= $form->field($messageForm, 'message')->widget(CKEditor::className(),[
										'editorOptions' => [
											'id' => 'message_text',
											'class' => 'form-control',
											'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
											'inline' => false, //по умолчанию false
											'language' => 'ru',
											'extraPlugins' => 'image',
											'filebrowserUploadUrl' => 'uploads/files-upload/content-upload?category=content&CKEditor=content-content&CKEditorFuncNum=2&langCode=ru',
										],
									])->label(false); ?>
									<?= Html::error($messageForm, 'message', [
										'class'=>'error'
									]); ?>
								</div>
							</div>
<!--							<div class="form-group">-->
<!--								--><?php //= $form->field($messageForm, 'reCaptcha')->widget(
//									common\widgets\captcha\ReCaptcha::className(),
//									['siteKey' => '6Le3szsUAAAAAOMdQNGpbgKVumgxkm9cLBs5XPqP']
//								); ?>
<!--							</div>-->
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-primary']) ?>
								</div>
							</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
