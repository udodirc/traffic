<?php
use yii\helpers\Html;

$this->title = Yii::t('menu', 'Структура по уровням');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= Html::encode($this->title); ?></h4>
	            <?php
	            if(((isset($data)) && !empty($data))):
		            ?>
                    <div id="matrix-by-level">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <th><?= Yii::t('form', 'Логин спонсора'); ?></th>
                            <th><?= Yii::t('form', 'ID матрицы спонсора'); ?></th>
                            <th><?= Yii::t('form', 'ID матрицы партнера'); ?></th>
                            <th><?= Yii::t('form', 'Смотреть'); ?></th>
                            </thead>
				            <?php
				            foreach($data as $i => $matrixData):
					            ?>
                                <tr>
                                    <td>
							            <?= $matrixData['sponsor_login']; ?>
                                    </td>
                                    <td>
							            <?= $matrixData['matrix_id'].' - '.$matrixData['parent_matrix_login']; ?>
                                    </td>
                                    <td>
							            <?= $matrixData['id'].' - '.$matrixData['login']; ?>
                                    </td>
                                    <td>
							            <?= Html::a('Смотреть', \Yii::$app->request->BaseUrl.'/partners/matrix-by-id/'.$structure_number.'/'.$matrix.'/'.$demo.'/'.$matrixData['id'].'/'.$matrixData['partner_id'], [
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
	            ?>
            </div>
		</div>
	</div>
</div>
