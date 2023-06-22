<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\tickets\models\Tickets */
/* @var $form yii\widgets\ActiveForm */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= Html::encode($this->title); ?></h2>
<br/>
<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><?= Html::encode($this->title); ?></h4>
				<?= $this->render('_form', [
					'model' => $model,
					'id' => $id,
				]) ?>
			</div>
		</div>
	</div>
</div>
