<?php
use yii\helpers\Html;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success" data-collapsed="0">
		<!-- panel head -->
			<div class="panel-heading" style="height: 37px">
                <div style="display: flex !important; padding: 10px">
                    <div><?= Html::a($model->title, $model->link, [
		                    'title'=>$model->title,
		                    'target'=>'_blank'
	                    ]); ?>
                    </div>
                    <div style="margin-left: auto;">
                        <i class="mdi mdi-cellphone-link icon-sm align-self-center mr-3">
                            <?= Html::a('Редактировать', \Yii::$app->request->BaseUrl.'/partners/text-advert/edit/'.$model->id, []); ?>
                        </i>
                        <i class="mdi mdi-cellphone-link icon-sm align-self-center mr-3">
                            <?= Html::a('Удалить', \Yii::$app->request->BaseUrl.'/partners/text-advert/delete/'.$model->id, []); ?>
                        </i>
                    </div>
                </div>
            </div>
			<!-- panel body -->
			<div class="panel-body media">
				<?= Html::a($model->title, $model->link, [
					'title'=>$model->title,
					'target'=>'_blank'
				]); ?>
			</div>	
		</div>
	</div>
</div>
