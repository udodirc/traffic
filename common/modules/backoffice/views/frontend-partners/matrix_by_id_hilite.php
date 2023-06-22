<?php
use yii\helpers\Html;

$this->title = Yii::t('form', 'Структура по матрицам');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= Html::encode($this->title); ?></h4>
	            <?php
	            if(isset($data[$id]['child_structure']) && !empty($data[$id]['child_structure'])):
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
                        <div class="matrix-binary-block" style="width:100%">
                            <div class="matrix-binary">
                                <div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.$id.'<br/>'.((isset($data[$id]['child_structure'][$id]['data']['clone']) && $data[$id]['child_structure'][$id]['data']['clone'] > 0) ? '<span class="clone">'.Yii::t('form', 'клон').'</span>' : ''); ?></div>
                                <div class="matrix-partner center status_<?= ($gold_token > 0) ? 'gold_token' : $model->status; ?>">
						            <?= ($gold_token > 0) ? '<div class="gold_token">$'.$gold_token.'</div>' : ''; ?>
                                </div>
                                <div class="matrix-login center">
						            <?= $model->login; ?>
                                </div>
                            </div>
                        </div>
			            <?php
			            if((isset($data[$id]['child_structure'])) && !empty($data[$id]['child_structure'])):
				            reset($data[$id]['child_structure']);
				            $firstKey = key($data[$id]['child_structure']);
				            $matrixData = $data[$id]['child_structure'][$firstKey]['child'];

				            foreach($matrixData as $matrixID => $levelData):
					            ?>
                                <div style="float:left; width:50%; overflow:hidden;">
                                    <div class="matrix-binary-block" style="width:100%">
                                        <div class="matrix-binary">
                                            <div class="matrix-login center"><?= Yii::t('form', 'ID матрицы').' - '.((isset($levelData['data']['id'])) ? $levelData['data']['id'].'<br/>'.((isset($levelData['data']['clone']) && $levelData['data']['clone'] > 0) ? '<span class="clone">'.Yii::t('form', 'клон').'</span>' : '') : ''); ?></div>
                                            <div class="matrix-partner center status_<?= ($levelData['data']['gold_token'] > 0) ? 'gold_token' : ((isset($levelData['data'])) ? $levelData['data']['status'] : ''); ?>">
									            <?= ($levelData['data']['gold_token'] > 0) ? '<div class="gold_token">$'.$levelData['data']['gold_token'].'</div>' : ''; ?>
                                            </div>
                                            <div class="matrix-login center">
									            <?= (isset($levelData['data']['login']) ? $levelData['data']['login'] : ''); ?>
                                            </div>
                                        </div>
                                    </div>
						            <?php
						            if(count($matrixData) < 2):
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
													            <?= ((isset($levelData2['data'])) ? $levelData2['data']['login'] : ''); ?>
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
																            <?= ((isset($levelData3['data'])) ? $levelData3['data']['login'] : ''); ?>
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
	            <?php
	            endif;
	            ?>
			</div>
		</div>
	</div>
</div>
