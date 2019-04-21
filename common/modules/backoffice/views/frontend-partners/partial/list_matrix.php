<?php
use yii\helpers\Html;
	
if(((isset($matrixData['levels'])) && !empty($matrixData['levels']))):				
?>
<div id="list-matrix">
	<table class="table table-striped table-bordered">
		<thead>
			<th><?= Yii::t('form', 'Уровень'); ?></th>
			<th><?= Yii::t('form', 'Кол-во людей'); ?></th>
			<th><?= Yii::t('form', 'Кол-во денег'); ?></th>
			<th><?= Yii::t('form', 'Реальное кол-во людей'); ?></th>
			<th><?= Yii::t('form', 'Реальное кол-во денег'); ?></th>
			<th style="width:50px;"><?= Yii::t('form', 'Структура'); ?></th>
		</thead>
		<?php
		$i = 0;
		$totalPartnersCount = 0;
		$totalPayOff = 0;
		$realPartnersCount = 0;
		$realPayOff = 0;
		$totalRealPartnersCount = 0;
		$totalRealPayOff = 0;
		
		foreach($matrixData['levels'] as $level => $matrix):
		
			if($i == 0):
			
				$partnersCount = 1;
			
			elseif($i == 1):
			
				$partnersCount = 2;
				
			else:
				
				$partnersCount = pow($matrix_wide, $i);
			
			endif;
			
			$totalPartnersCount+= $partnersCount;
			$totalPayOff+= ($partnersCount * $pay_off);
			$realPartnersCount = (isset($matrixData['levels'][$level])) ? count($matrixData['levels'][$level]) : 0;
			$realPayOff = (isset($matrixData['levels'][$level])) ? (count($matrixData['levels'][$level]) * $pay_off) : 0;
			$totalRealPartnersCount+= $realPartnersCount;
			$totalRealPayOff+= ($realPartnersCount * $pay_off);
		?>
		<tr>
			<td><?= ($i > 0) ? $i : Yii::t('form', 'Вы'); ?></td>
			<td><?= ($i > 0) ? $partnersCount : Yii::t('form', 'Вы'); ?></td>
			<td><?= ($i > 0) ? ($partnersCount * $pay_off) : 0; ?></td>
			<td><?= ($i > 0) ? $realPartnersCount : Yii::t('form', 'Вы'); ?></td>
			<td><?= ($i > 0) ? $realPayOff : 0; ?></td>
			<td>
				<?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \Yii::$app->request->BaseUrl.'/partners/matrix-level/'.$structureNumber.'/'.$matrixNumber.'/'.$demo.'/'.$matrixID.'/'.$level, [
						'title' => Yii::t('form', 'Смотреть'),
						'target' => '_blank',
					]);
				?>
			</td>
		</tr>
		<?php
			$i++;
		endforeach;
		?>
		<tr>
			<td colspan="6"><?= '<span style="font-weight:bold;">'.Yii::t('form', 'Итого').':</span>'; ?></td>
		</tr>
		<tr>
			<td></td>
			<td><?= $totalPartnersCount; ?></td>
			<td><?= $totalPayOff; ?></td>
			<td><?= $totalRealPartnersCount; ?></td>
			<td style="width:50px;"><?= $totalRealPayOff; ?></td>
		</tr>
	</table>
</div>
<?php
endif;
