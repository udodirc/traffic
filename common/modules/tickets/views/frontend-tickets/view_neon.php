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
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger">
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<div class="row">
	<div class="col-md-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Тема').':&nbsp;'.Html::encode($this->title); ?></h5>
			</div>
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
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Сообщение'); ?></div>
				<div class="panel-options"></div>
			</div>
			<div class="panel-body">
			<?php $form = ActiveForm::begin([
				'id'=>'inline-validation',
				'options' => [
					'class'=>'form-horizontal'
				],
				'action'=>'send-message?id='.$ticketModel->id
			]); 
			?>
			<div class="form-group">
				<?= Html::activeLabel($messageForm, 'text', [
					'class'=>'col-sm-2 control-label',
					'label' => Yii::t('form', 'Сообщение')
				]); ?>
				<div class="col-sm-8">
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
						'class'=>'error'
					]); ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8" align="right">
					<?= Html::submitButton(Yii::t('form', 'Отправить'), ['class' => 'btn btn-success']) ?>
				</div>
			</div>
			<?php ActiveForm::end(); ?>				
			</div>		
		</div>
	</div>
</div>
