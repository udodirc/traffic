<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;
use common\components\FileHelper;

/* @var $this yii\web\View */
/* @var $model common\modules\landings\models\Landings */

$this->title = Yii::t('form', 'Редактировать: ', [
    'modelClass' => 'Landings',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Landings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('form', 'Редактировать');		
?>
<div class="landings-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'styles')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'js')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'inner_style')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'inner_js')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>
    <div id="dropzone">
		<div class="btn_box">
			<div class="btn">Выберите с компьютера</div>
		</div>
		<?php if ($url !== ''): ?>
			<?= FileUpload::widget([
				'model' => $model,
				'attribute' => 'file',
				'url' => ['/uploads/files-upload/upload?category='.$category.'&tmp=tmp'], // your url, this is just for demo purposes,
				'options' => ['accept' => 'image/*'],
				'clientOptions' => [
					'maxFileSize' => 2000000
				],
				// Also, you can specify jQuery-File-Upload events
				// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
				'clientEvents' => [
					'fileuploaddone' => 'function(e, data) 
					{
						var name = data.files[0].name;
						var item_id = ($("div.file_wrap").length > 0) ? $("div.file_wrap").length : 0;
						item_id += 1;
						
						if($(".file_wrap").length > 0)
						{	
							$("<div class=\"file_wrap\" file=\"" + name + "\" category=\"'.$category.'\" file_type=\"tmp\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div>\"" + name + "\"</div></div>").insertAfter($(".file_wrap:last"));
						}
						else
						{
							
							$("#files-upload").append("<div class=\"file_wrap\" file=\"" + name + "\" category=\"'.$category.'\" file_type=\"tmp\" item_id=\"" + item_id + "\"><div align=\"right\"><a class=\"file_delete\" href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></div><div>\"" + name + "\"</div></div>");
						}
					}',
					'fileuploadfail' => 'function(e, data)
					{
						console.log(e);
						console.log(data);
					}',
				],
			]);?>
		<?php endif; ?>	
		</div>
		<?php if ($category != '' && $filesDir != '' && !empty($assetsFiles)): ?>
			<?=$this->render('partial/files_list', [
				'files' => $assetsFiles,
				'category' => $category,
				'filesDir' => $filesDir,
				'model' => $model,
			]);?>
		<?php endif; ?>
		<?php if ($category != '' && $filesDir != '' && !empty($imagesFiles)): ?>
			<?=$this->render('partial/files_list', [
				'files' => $imagesFiles,
				'category' => $category,
				'filesDir' => $filesDir,
				'model' => $model,
			]);?>
		<?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('form', 'Создать') : Yii::t('form', 'Обновить'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
