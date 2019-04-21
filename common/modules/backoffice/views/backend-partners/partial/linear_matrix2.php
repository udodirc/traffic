<?php
use yii\helpers\Html;
if(isset($matrixData) && !empty($matrixData)):		
foreach($matrixData as $matrixID => $matrixStructure)
{
?>
<div class="matrix-linear-block-wrap">
	<div class="level matrix_number">
		<?= Yii::t('form', 'Номер матрицы').' - '.$matrixID; ?>
	</div>
	<? if($admin): ?>
	<div class="sponsor-structure-wrap">
		<div class="sponsor-structure">
			<?= Html::a(Yii::t('form', 'Структура спонсора'), ['backend-partners/sponsor-structure?matrix_number='.$matrixNumber.'&id='.$id.'&matrix_id='.$matrixID.'&demo='.(($demo) ? 1 : 0)], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<? endif; ?>
	<?php
	if(isset($matrixStructure['matrix_structure'])):
		foreach($matrixStructure['matrix_structure'] as $i => $item)
		{	
		?>
			<div class="matrix-linear-block">
				<div class="matrix-partner center status_<?= $item['status']; ?>"></div>
				<div class="matrix-login">
					<?= Html::a($item['login'], (($demo) ? 'demo-' : '').'structure?id='.$item['id']); ?>
				</div>
			</div>
		<?php
		}
	endif;
	?>
</div>
<div class="matrix_info">
<?php
if((isset($matrixStructure['matrix_info'])) && !empty($matrixStructure['matrix_info'])):
?>
	<table>
		<thead>
			<th><?= Yii::t('form', 'ID матрицы'); ?></th>
			<th><?= Yii::t('form', 'Всего заработано'); ?></th>
			<th><?= Yii::t('form', 'Дата оплаты'); ?></th>
			<th><?= Yii::t('form', 'Статус'); ?></th>
		</thead>
		<?php
		foreach($matrixStructure['matrix_info'] as $type => $matrixInfo):
		?>
		<tr>
			<td><?= $matrixID; ?></td>
			<td>$<?= $matrixInfo[0]; ?></td>
			<td><?= gmdate("Y-m-d H:i:s", $matrixInfo[1]); ?></td>
			<td><?= ($matrixInfo[2] > 0) ? Yii::t('form', 'Закрыта!').' - '.gmdate("Y-m-d H:i:s", $matrixInfo[2]) : Yii::t('form', 'В ожидании закрытия'); ?></td>
		</tr>
		<?php
		endforeach;
		?>
	</table>
<?php
endif;
?>
</div>	
<?php
}
endif;
?>
<div class="clear"></div>
