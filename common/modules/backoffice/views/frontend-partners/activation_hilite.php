<?php
use yii\helpers\Html;
use common\components\ContentHelper;
use common\models\Service;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? Html::encode($this->params['title']) : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= $this->title; ?></h2>
<br/>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= Yii::t('form', 'Информация'); ?></h4>
                <?= ContentHelper::outPutContent($content);?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<?php if (Yii::$app->session->hasFlash('success')): ?>
			<div class="alert alert-success" role="alert">
				<?= Html::encode(Yii::$app->session->getFlash('success')); ?>
			</div>
			<?php elseif (Yii::$app->session->hasFlash('error')): ?>
			<div class="alert alert-danger" role="alert">
				<?= Html::encode(Yii::$app->session->getFlash('error')); ?>
			</div>
			<?php endif; ?>
			<div class="card-body">
				<h4 class="card-title"><?= $this->title; ?></h4>
				<div class="panel-content activation">
					<?php if (Service::isActionAllowed('is_activation_allowed')): ?>
                        <div class="activation_btn">
							<?= Html::a(Yii::t('form', 'Оплатить'), ['pay', 'id' => $id, 'payment_type' => 2, 'structure' => 1, 'matrix' => 1, 'places' => 1], ['class' => 'btn btn-wide btn-success']) ?>
                        </div>
<!--						--><?php //if ($model->matrix_1 > 0): ?>
<!--						<div class="activation_btn">-->
<!--							--><?php //= Html::a(Yii::t('form', 'Оплатить короткие матрицы'), ['reserve-places', 'id' => $id, 'payment_type' => 2, 'structure' => 1], ['class' => 'btn btn-wide btn-success']) ?>
<!--						</div>-->
<!--						--><?php //else: ?>
<!--						<div class="activation_btn">-->
<!--							--><?php //= Html::a(Yii::t('form', 'Оплатить'), ['pay', 'id' => $id, 'payment_type' => 2, 'structure' => 1, 'matrix' => 1, 'places' => 1], ['class' => 'btn btn-wide btn-success']) ?>
<!--						</div>-->
<!--						--><?php //endif; ?>
					<?php endif; ?>
				</div>
				<div class="panel-content">
                    <?= ContentHelper::outPutContent($tile_content);?>
				</div>
			</div>
		</div>
	</div>
</div>
