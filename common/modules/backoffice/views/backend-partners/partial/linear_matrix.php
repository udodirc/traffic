<?php
use yii\helpers\Html;

if(isset($matrixData['matrix_structure']) && !empty($matrixData['matrix_structure'])):
?>
<div class="matrix-linear-block-wrap">
	<div class="level matrix_number">
		<?= Yii::t('form', 'Номер матрицы').' - '.$matrixNumber; ?>
	</div>
	<?php if($admin): ?>
	<div class="sponsor-structure-wrap">
		<div class="sponsor-structure">
			<?= Html::a(Yii::t('form', 'Структура спонсора'), ['backend-partners/sponsor-structure?matrix_number='.$matrixNumber.'&id='.$id.'&matrix_id='.$matrixID.'&demo='.(($demo) ? 1 : 0)], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<?php endif; ?>
	<?php
	foreach($matrixData['matrix_structure'] as $i => $item)
	{	
	?>
		<div class="matrix-linear-block">
			<div class="matrix-partner center status_<?= $item['status']; ?>"></div>
			<div class="matrix-login">
				<?= Html::a($item['login'], \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/structure?id='.$item['id'], []); ?>
			</div>
		</div>
	<?php
	}
	?>
</div>
<div class="matrix_info">
	<?php
	if((isset($matrixData['matrix_info'])) && !empty($matrixData['matrix_info'])):
	?>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_content">
				<table class="table">
					<thead>
						<th><?= Yii::t('form', 'ID матрицы'); ?></th>
						<th><?= Yii::t('form', 'Всего заработано'); ?></th>
						<th><?= Yii::t('form', 'Дата оплаты'); ?></th>
						<th><?= Yii::t('form', 'Статус'); ?></th>
					</thead>
					<?php
					foreach($matrixData['matrix_info'] as $type => $matrixInfo):
					?>
					<tr>
						<td><?= $matrixNumber; ?></td>
						<td>$<?= $matrixInfo[0]; ?></td>
						<td><?= gmdate("Y-m-d H:i:s", $matrixInfo[1]); ?></td>
						<td><?= ($matrixInfo[2] > 0) ? '<span style="color:red;">'.Yii::t('form', 'Закрыта!').'</span> - '.gmdate("Y-m-d H:i:s", $matrixInfo[2]) : Yii::t('form', 'В ожидании закрытия'); ?></td>
					</tr>
					<?php
					endforeach;
					?>
				</table>
			</div>
		</div>
	</div>
	<?php
	endif;
	?>
</div>	
<?php
endif;
?>
<div class="clear"></div>
