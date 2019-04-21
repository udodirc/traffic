<?php
use yii\helpers\Html;

if (Yii::$app->session->hasFlash('success')): ?>
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
<?php endif;
	
if(((isset($data)) && !empty($data))):				
?>
<div id="matrix-by-level">
	<table class="table table-striped table-bordered">
		<thead>
			<th><?= Yii::t('form', 'Логин спонсора'); ?></th>
			<th><?= Yii::t('form', 'ID матрицы спонсора'); ?></th>
			<th><?= Yii::t('form', 'ID матрицы партнера'); ?></th>
			<th><?= Yii::t('form', 'Дата оплаты'); ?></th>
			<th></th>
		</thead>
		<?php
		foreach($data as $i => $matrixData):
		?>
		<tr>
			<td>
			<?= Html::a($matrixData['sponsor_login'], \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partner-info?id='.$matrixData['sponsor_id'], [
					'title' => Yii::t('form', 'Логин спонсора'),
					'target' => 'blank',
				]);
			?>
			</td>
			<td>
				<?= $matrixData['matrix_id'].' - '.Html::a($matrixData['parent_matrix_login'], \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partner-info?id='.$matrixData['parent_id'], [
					'title' => Yii::t('form', 'Логин спонсора матрицы'),
					'target' => 'blank',
				]); 
				?>
			</td>
			<td>
				<?= $matrixData['id'].' - '.Html::a($matrixData['login'], \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/partner-info?id='.$matrixData['partner_id'], [
					'title' => Yii::t('form', 'Логин партнера'),
					'target' => 'blank',
				]);
				?>
			</td>
			<td>
				<?= date("Y-m-d H:i:s", $matrixData['open_date']); ?>
			</td>
			<td>
				<?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/matrix-by-id?structure='.$structure_number.'&number='.$matrix.'&demo='.$demo.'&id='.$matrixData['id'].'&partner_id='.$matrixData['partner_id'], [
					'title' => Yii::t('form', 'Смотреть'),
					'target' => 'blank',
				]);
				?>
			</td>
		</tr>
		<?php
		endforeach;
		?>
	</table>
</div>
<?php
endif;
