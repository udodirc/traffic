<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('menu', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container center">
	<div class="row">
		<div class="panel-wrapper panel-login"  style="margin-top: 100px;">
			<div class="panel" style="margin: 0;">
				<?php if (Yii::$app->session->hasFlash('success')): ?>
				<div class="notice success">
					<span>
						<strong><?= Html::encode(Yii::$app->session->getFlash('success')).' - '.Html::a(Yii::t('form', 'Личный кабинет'), Url::base(true).'/login', []); ?></strong>
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
				<div class="content">
					<?= ($content !== null) ? $content->content : ''?>
				</div>
			</div>
		</div>
	</div>
</div>
