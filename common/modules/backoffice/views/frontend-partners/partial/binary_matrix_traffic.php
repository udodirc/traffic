<?php
use yii\helpers\Html;

if(isset($matrixData['child_structure']) && !empty($matrixData['child_structure'])):				
?>
	<div class="matrix-binary-block-wrap">
		<div class="matrix-binary-block" style="width:100%">
			<div class="matrix-binary">
				<div class="matrix-login center"><?= Yii::t('form', 'ID площадки').' - '.$matrixID; ?></div>
				<div class="matrix-partner center status_<?= $model->status; ?>"></div>
				<div class="matrix-login center">
					<?= $model->login; ?>
				</div>
			</div>
		</div>
		<?php
		if((isset($matrixData['child_structure'])) && !empty($matrixData['child_structure'])):
			reset($matrixData['child_structure']);
			$firstKey = key($matrixData['child_structure']);
			$data = $matrixData['child_structure'][$firstKey]['child'];
			
			foreach($data as $matrixID => $levelData):
			?>
			<div style="float:left; width:50%; overflow:hidden;">
				<div class="matrix-binary-block" style="width:100%">
					<div class="matrix-binary">
						<div class="matrix-login center"><?= Yii::t('form', 'ID площадки').' - '.((isset($levelData['data']['id'])) ? $levelData['data']['id'] : ''); ?></div>
						<div class="matrix-partner center status_<?= ($levelData['data']['gold_token'] > 0) ? 'gold_token' : ((isset($levelData['data'])) ? $levelData['data']['status'] : ''); ?>">
							<?= ($levelData['data']['gold_token'] > 0) ? '<div class="gold_token">$'.$levelData['data']['gold_token'].'</div>' : ''; ?>
						</div>
						<div class="matrix-login center">
							<?= (isset($levelData['data']['login'])) ? $levelData['data']['login'] : ''; ?>
						</div>
					</div>
				</div>
				<?php
				if(count($data) < 2):
				?>
					<div class="matrix-binary-block" style="width:100%">
						<div class="matrix-binary">
							<div class="matrix-login center"></div>
							<div class="no-partner"></div>
							<div class="matrix-login center"></div>
						</div>
					</div>
				<?php
				endif;
				if(isset($levelData['child'])):
					if(count($levelData['child']) > 0):
						foreach($levelData['child'] as $matrixID2 => $levelData2):
						?>
							<div style="float:left; width:50%; overflow:hidden;">
								<div class="matrix-binary-block" style="width:100%">
									<div class="matrix-binary">
										<div class="matrix-login center"><?= Yii::t('form', 'ID площадки').' - '.((isset($levelData2['data'])) ? $levelData2['data']['id'] : ''); ?></div>
										<div class="matrix-partner center status_<?= ($levelData2['data']['gold_token'] > 0) ? 'gold_token' : ((isset($levelData2['data'])) ? $levelData2['data']['status'] : ''); ?>">
											<?= ($levelData2['data']['gold_token'] > 0) ? '<div class="gold_token">$'.$levelData2['data']['gold_token'].'</div>' : ''; ?>
										</div>
										<div class="matrix-login center">
											<?= (isset($levelData2['data'])) ? $levelData2['data']['login'] : ''; ?>
										</div>
									</div>
								</div>
								<div style="float:left; width:100%; overflow:hidden;">
								<?php
								if(isset($levelData2['child']) && count($levelData2['child']) > 0):
									foreach($levelData2['child'] as $matrixID3 => $levelData3):
									?>
										<div class="matrix-binary-block" style="width:50%">
											<div class="matrix-binary">
												<div class="matrix-login center"><?= Yii::t('form', 'ID площадки').' - '.((isset($levelData3['data'])) ? $levelData3['data']['id'] : ''); ?></div>
												<div class="matrix-partner center status_<?= ($levelData3['data']['gold_token'] > 0) ? 'gold_token' : ((isset($levelData3['data'])) ? $levelData3['data']['status'] : ''); ?>">
													<?= ($levelData3['data']['gold_token'] > 0) ? '<div class="gold_token">$'.$levelData3['data']['gold_token'].'</div>' : ''; ?>
												</div>
													<?= (isset($levelData3['data'])) ? $levelData3['data']['login'] : ''; ?>
												</div>
											</div>
										</div>
									<?php
									endforeach;
									if(count($levelData2['child']) < 2):
									?>
										<div class="matrix-binary-block" style="width:100%">
											<div class="matrix-binary">
												<div class="matrix-login center"></div>
												<div class="no-partner"></div>
												<div class="matrix-login center"></div>
											</div>
										</div>
									<?php
									endif;
								endif;	
								?>	
								</div>	
							</div>
						<?php
						endforeach;
						if(count($levelData['child']) < 2):
						?>
							<div class="matrix-binary-block" style="width:100%">
								<div class="matrix-binary">
									<div class="matrix-login center"></div>
									<div class="no-partner"></div>
									<div class="matrix-login center"></div>
								</div>
							</div>
						<?php
						endif;
					endif;
				endif;
				?>
			</div>
			<?php
			endforeach;
		endif;
		?>
	</div>
	<div class="matrix_info">
	<?php
	if((isset($matrixData['matrix_info'])) && !empty($matrixData['matrix_info'])):
	?>
		<table class="table table-striped table-bordered">
			<thead>
				<th><?= Yii::t('form', 'ID площадки'); ?></th>
				<th><?= Yii::t('form', 'Всего заработано'); ?></th>
				<th><?= Yii::t('form', 'Баллы'); ?></th>
				<th><?= Yii::t('form', 'Дата оплаты'); ?></th>
				<th><?= Yii::t('form', 'Статус'); ?></th>
			</thead>
			<?php
			foreach($matrixData['matrix_info'] as $type => $matrixInfo):
			?>
			<tr>
				<td><?= $matrixNumber; ?></td>
				<td>$<?= $matrixInfo[0]; ?></td>
				<td><?= $matrixInfo[1]; ?></td>
				<td><?= gmdate("Y-m-d H:i:s", $matrixInfo[2]); ?></td>
				<td><?= ($matrixInfo[3] > 0) ? Yii::t('form', 'Закрыта!').' - '.gmdate("Y-m-d H:i:s", $matrixInfo[3]) : Yii::t('form', 'В ожидании закрытия'); ?></td>
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
endif;
