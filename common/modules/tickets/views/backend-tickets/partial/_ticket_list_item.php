<?php
use yii\helpers\Html;
?>
<div class="row<?= ($model->type > 1) ? '' : '-partner'; ?>">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-header">
				<span class="ticket-header <?= ($model->type > 1) ? 'green' : 'brown'; ?>"><?= Yii::t('form', 'От').':&nbsp;'.(($model->type > 1) ? $model->username : $ticketModel->partner->login).'&nbsp;-&nbsp;'.date("Y-m-d H:m:s", $model->created_at); ?></span>
				<span class="right">
					<?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'delete/'.$model->id.'/'.$ticketModel->id, []); ?>
				</span>
            </div>
            <div class="panel-content">
				<p>
					<?= $model->text; ?>
				</p>
            </div>
		</div>
	</div>
</div>
