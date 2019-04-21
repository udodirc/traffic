<div class="col-lg-12" id="<?= ($model->type > 1) ? '' : '-partner'; ?>">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="ticket-header <?= ($model->type > 1) ? 'green' : 'brown'; ?>"><?= Yii::t('form', 'От').':&nbsp;'.(($model->type > 1) ? $model->username : $ticketModel->partner->login).'&nbsp;-&nbsp;'.date("Y-m-d H:m:s", $model->created_at); ?></span>
			<div class="pull-right news">
				<?= date("Y-m-d", $model->created_at); ?>
			</div>
			<div class="clearfix"></div>        
		</div>
		<div class="panel-body">
			<p>
				<?= $model->text; ?>
			</p>
		</div>
	</div>
</div>
