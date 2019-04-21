<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Информация'); ?></h5>
			</div>
            <div class="ibox-content">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Заработок'); ?></h5>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Площадки').'&nbsp;-&nbsp;'.Yii::t('form', 'ожидаемый'); ?></h5>
			</div>
            <div class="ibox-content">
				<?= $this->render('partial/matrices', ['matrices' => $model->demo_matrix, 'id' => $id, 'demo' => true]); ?>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Yii::t('form', 'Площадки').'&nbsp;-&nbsp;'.Yii::t('form', 'реальный'); ?></h5>
			</div>
            <div class="ibox-content">
				<?= $this->render('partial/matrices', ['matrices' => $model->matrix, 'id' => $id, 'demo' => false]); ?>
			</div>
		</div>
	</div>
</div>
