<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('menu', 'Восстановление пароля');
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="moduletable col-sm-12">
				<div class="module_container">
					<ul class="breadcrumb">
						<li class="active"><span><?= $this->title; ?></span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="content">
	<div class="container">
		<div class="row">
			<div class="content-inner">
				<div id="component" class="col-sm-12">
					<main role="main">
						<div class="page_header">
							<h2 class="heading-style-2 visible-first">
								<span class="item_title_part_0 item_title_part_odd item_title_part_first_half item_title_part_first"><?= $this->title; ?></span> 
							</h2>
						</div>
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
						<div class="restore-password-form">
							<?php $form = ActiveForm::begin([
								'options' => [
									//'class' => 'mailform', 
								],
								'id' => 'restore-password-form',
							]); ?>
							<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<?= $form->field($model, 'email', [
										'template' => '<div class="col-sm-12">
											<div class="controls">
												{input}{hint}{error}
											</div>
										</div>',
										'inputOptions' => [
											'placeholder' => Yii::t('form', 'Email'),
											'class' => '',
										]]); ?>
									</div>
								</div>
								<div class="col-md-12">
									<?= Html::submitButton(Yii::t('form', 'Выслать'), ['class' => 'btn pull-right']) ?>
								</div>
							</div>
							<?php ActiveForm::end(); ?>
						</div>
					</main>
				</div>
			</div>
		</div>
	</div>
</div>
