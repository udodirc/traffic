<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('form', 'Сообщения');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailing-form">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="notice success">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="notice error">
		<span>
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</span>
	</div><!-- /.flash-success -->
	<?php endif; ?>
    <?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'type')->radioList([
		'1'=>Yii::t('form', 'Все партнеры'), 
		'2'=>Yii::t('form', 'Рассылка по логинам'),
		'3'=>Yii::t('form', 'Рассылка по ID')
	], 
	['item' => function($index, $label, $name, $checked, $value) {
		$return = '<label class="modal-radio">';
        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" id="mailing_type_'.$value.'" class="mailing_type" >';
        $return .= '<span>  ' . ucwords($label) . '</span>';
        $return .= '</label>';
        
        return $return;
    }]); ?>
    <div class="mailing_form" id="mailing_form_2">
		<?= $form->field($model, 'login_list')->textArea(['rows' => '6', 'id' => 'login_list']) ?>
    </div>
    <div class="mailing_form" id="mailing_form_3">
		<?= $form->field($model, 'id_from')->textInput(['maxlength' => 10, 'style' => 'width:100px;']) ?>
		<?= $form->field($model, 'id_to')->textInput(['maxlength' => 10, 'style' => 'width:100px;']) ?>
    </div>
    <?= $form->field($model, 'subject')->textInput(['maxlength' => 100, 'style' => 'width:760px;']) ?>
    <?= $form->field($model, 'message')->textArea(['rows' => '6', 'id' => 'message']) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'Добавить'), ['class' => 'button-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
