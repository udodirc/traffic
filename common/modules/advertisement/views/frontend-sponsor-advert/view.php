<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\advertisement\models\SponsorAdvert */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Sponsor Adverts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h6 class="panel-title txt-dark"><?= Html::encode($this->title); ?></h6>
			</div>
            <div class="ibox-content">
				<p>
					<?= Html::a(Yii::t('form', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
					<?= Html::a(Yii::t('form', 'Удалить'), ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => Yii::t('form', 'Are you sure you want to delete this item?'),
							'method' => 'post',
						],
					]) ?>
				</p>
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'id',
						'name',
						[
							'attribute' => 'created_at', 
							'label' => Yii::t('form', 'Дата создания'),
							'format' => ['date', 'php:Y-m-d'],
							'filter'=>false,
						],
					],
				]) ?>
			</div>
		</div>	
	</div>
</div>
