<?php
use yii\helpers\Html;

if(isset($matrixData['child_structure']) && !empty($matrixData['child_structure'])):				
?>
	<?php if (Yii::$app->session->hasFlash('success')): ?>
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
	<?php endif; ?>
	<div class="matrix-binary-block-wrap">
		<div style="width:100%; text-align:center; margin:20px 0;">
			<?= Yii::t('form', 'Спонсор матрицы') ?> - 
			<?= Html::a(Yii::t('form', 'смотреть'), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/sponsor-matrix/'.$matrixID.'/'.$structureNumber.'/'.$matrixNumber.'/'.$demo.'/'.$list_view, []); ?>
		</div>
		<div class="matrix-binary-block" style="width:100%">
			<div class="matrix-binary">
				<div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.$matrixID.'<br/>'.((isset($matrixData['child_structure'][$matrixID]['data']['clone']) && $matrixData['child_structure'][$matrixID]['data']['clone'] > 0) ? '<span class="clone">'.Yii::t('form', 'клон').'</span>' : ''); ?></div>
				<div class="matrix-partner center status_<?= ($gold_token > 0) ? 'gold_token' : $model->status; ?>">
					<?= ($gold_token > 0) ? '<div class="gold_token">$'.$gold_token.'</div>' : ''; ?>
				</div>
				<div class="matrix-login center">
					<?= Html::a($model->login, (($demo) ? 'demo-' : '').\Yii::$app->request->BaseUrl.'/backoffice/backend-partners/structure?id='.$model->id, []).'&nbsp;-&nbsp;'.Html::a('<span class="glyphicon glyphicon-edit"></span>', ['change-admin?id='.$matrixID.'&structure='.$structureNumber.'&number='.$matrixNumber.'&demo='.$demo.'&partner_id='.$model->id], [
						[
							'data' => [
								'confirm' => Yii::t('form', 'Вы хотите сделать замену?'),
								'method' => 'post',
							]
						]
					]); ?>
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
						<div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.((isset($levelData['data']['id'])) ? $levelData['data']['id'].'<br/>'.((isset($levelData['data']['clone']) && $levelData['data']['clone'] > 0) ? '<span class="clone">'.Yii::t('form', 'клон').'</span>' : '') : ''); ?></div>
						<div class="matrix-partner center status_<?= ($levelData['data']['gold_token'] > 0) ? 'gold_token' : ((isset($levelData['data'])) ? $levelData['data']['status'] : ''); ?>">
							<?= ($levelData['data']['gold_token'] > 0) ? '<div class="gold_token">$'.$levelData['data']['gold_token'].'</div>' : ''; ?>
						</div>
						<div class="matrix-login center">
							<?= Html::a(((isset($levelData['data']['login'])) ? $levelData['data']['login'] : ''), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/structure?id='.((isset($levelData['data']['partner_id'])) ? $levelData['data']['partner_id'] : ''), []).(($levelData['data']['partner_id'] != 1) ? '&nbsp;-&nbsp;'.Html::a('<span class="glyphicon glyphicon-edit"></span>', ['change-admin?id='.$levelData['data']['id'].'&structure='.$structureNumber.'&number='.$matrixNumber.'&demo='.$demo.'&partner_id='.$model->id], 
							[
								'data' => [
									'confirm' => Yii::t('form', 'Вы хотите сделать замену?'),
									'method' => 'post',
								]
							]) : ''); 
							?>
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
										<div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.((isset($levelData2['data'])) ? $levelData2['data']['id'].'<br/>'.((isset($levelData2['data']['clone']) && $levelData2['data']['clone'] > 0) ? '<span class="clone">'.Yii::t('form', 'клон').'</span>' : '') : ''); ?></div>
										<div class="matrix-partner center status_<?= ($levelData2['data']['gold_token'] > 0) ? 'gold_token' : ((isset($levelData2['data'])) ? $levelData2['data']['status'] : ''); ?>">
											<?= ($levelData2['data']['gold_token'] > 0) ? '<div class="gold_token">$'.$levelData2['data']['gold_token'].'</div>' : ''; ?>
										</div>
										<div class="matrix-login center">
											<?= Html::a(((isset($levelData2['data'])) ? $levelData2['data']['login'] : ''), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/structure?id='.((isset($levelData2['data'])) ? $levelData2['data']['partner_id'] : ''), []).(($levelData2['data']['partner_id'] != 1) ? '&nbsp;-&nbsp;'.
											Html::a('<span class="glyphicon glyphicon-edit"></span>', ['change-admin?id='.$levelData2['data']['id'].'&structure='.$structureNumber.'&number='.$matrixNumber.'&demo='.$demo.'&partner_id='.$model->id], 
											[
												'data' => [
													'confirm' => Yii::t('form', 'Вы хотите сделать замену?'),
													'method' => 'post',
												]
											]) : ''); ?>
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
												<div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.((isset($levelData3['data'])) ? $levelData3['data']['id'].'<br/>'.((isset($levelData3['data']['clone']) && $levelData3['data']['clone'] > 0) ? '<span class="clone">'.Yii::t('form', 'клон').'</span>' : '') : ''); ?></div>
												<div class="matrix-partner center status_<?= ($levelData3['data']['gold_token'] > 0) ? 'gold_token' : ((isset($levelData3['data'])) ? $levelData3['data']['status'] : ''); ?>">
													<?= ($levelData3['data']['gold_token'] > 0) ? '<div class="gold_token">$'.$levelData3['data']['gold_token'].'</div>' : ''; ?>
												</div>
												<div class="matrix-login center">
													<?= Html::a(((isset($levelData3['data'])) ? $levelData3['data']['login'] : ''), \Yii::$app->request->BaseUrl.'/backoffice/backend-partners/structure?id='.(isset($levelData3['data']) ? $levelData3['data']['partner_id'] : ''), []).(($levelData3['data']['partner_id'] != 1) ? '&nbsp;-&nbsp;'.
													Html::a('<span class="glyphicon glyphicon-edit"></span>', ['change-admin?id='.$levelData3['data']['id'].'&structure='.$structureNumber.'&number='.$matrixNumber.'&demo='.$demo.'&partner_id='.$model->id], 
													[
														'data' => [
															'confirm' => Yii::t('form', 'Вы хотите сделать замену?'),
															'method' => 'post',
														]
													]) : ''); ?>
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
				<th><?= Yii::t('form', 'ID матрицы'); ?></th>
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
