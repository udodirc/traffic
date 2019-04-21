<?php
use yii\helpers\Html;

if(!empty($matrixData)):				
	foreach($matrixData as $matrixID => $matrixStructure):
	?>
		<div class="matrix-binary-block-wrap">
			<div class="level matrix_number">
				<?= Yii::t('form', 'ID матрицы').' - '.$matrixID; ?>
			</div>
			<? if($admin): ?>
			<div class="sponsor-structure-wrap">
				<div class="sponsor-structure">
					<?= Html::a(Yii::t('form', 'Структура спонсора'), ['backend-partners/sponsor-structure?matrix_number='.$matrixNumber.'&id='.$id.'&matrix_id='.$matrixID.'&demo='.(($demo) ? 1 : 0)], ['class' => 'btn btn-success']) ?>
				</div>
			</div>
			<? endif; ?>
			<div class="level">
				<div class="matrix-partner center status_<?= $model->status; ?>"></div>
				<div class="matrix-login center">
					<?= Html::a($model->login, (($demo) ? 'demo-' : '').'structure?id='.$model->id, []); ?>
				</div>
			</div>
			<?php
			if((isset($matrixStructure['matrix_structure'])) && !empty($matrixStructure['matrix_structure'])):
				reset($matrixStructure['matrix_structure']);
				$firstKey = key($matrixStructure['matrix_structure']);
				$counter = 1;
					
				foreach($matrixStructure['matrix_structure'] as $level => $levelData):
					if($level != $firstKey):
					?>
					<div class="level">	
					<?php	
						foreach($levelData as $matrixID => $matrixData):
							foreach($matrixData as $key => $partnerData):
							if(count($matrixData) > 1):
							?>	
							<div class="matrix-binary-block<?= ($counter == 2) ? ' level_2' : ''; ?>">
								<div class="matrix-binary">
									<div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.$partnerData['id']; ?></div>
									<div class="matrix-partner center status_<?= $partnerData['status']; ?>"></div>
									<div class="matrix-login center">
										<?= Html::a($partnerData['login'], (($demo) ? 'demo-' : '').'structure?id='.$partnerData['partner_id'], []); ?>
									</div>
								</div>
							</div>
							<?php
							else:
							?>	
							<div class="matrix-binary-block<?= ($counter == 2) ? ' level_2' : ''; ?>">
								<div class="matrix-binary">
									<div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.$matrixData[0]['id']; ?></div>
									<div class="matrix-partner center status_<?= $partnerData['status']; ?>"></div>
									<div class="matrix-login center">
										<?= Html::a($matrixData[0]['login'], (($demo) ? 'demo-' : '').'structure?id='.$matrixData[0]['partner_id'], []); ?>
									</div>
								</div>
							</div>
							<div class="matrix-binary-block<?= ($counter == 2) ? ' level_2' : ''; ?>">
								<div class="matrix-binary-empty"></div>
							</div>
							<?php
							endif;
							endforeach;
						endforeach;
						$counter++;
					?>
					</div>	
					<?php
					endif;
				endforeach;
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
	endforeach;
endif;
