<?php
use yii\helpers\Html;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success" data-collapsed="0">
		<!-- panel head -->
			<div class="panel-heading">
                <div class="text-advert-actions-block">
                    <div><?= Html::a($model->title, $model->link, [
		                    'title'=>$model->title,
		                    'target'=>'_blank'
	                    ]); ?>
                    </div>
                    <div class="text-advert-actions">
	                    <?php if ($model->status > 0): ?>
                        <i class="align-self-center mr-3 text-advert-action green">
                            Одобрено
                        </i>
	                    <?php else: ?>
                        <i class="align-self-center mr-3 text-advert-action red">
                            На рассмотрении
                        </i>|
	                    <?php endif; ?>
                        <i class="align-self-center mr-3 text-advert-action">
                            Общее количество баллов:&nbsp;<span class="green"><?= $model->balls; ?></span>
                        </i>|
                        <i class="align-self-center mr-3 text-advert-action">
                            Остаток баллов:&nbsp;<span class="red"><?= $model->counter; ?></span>
                        </i>|
                        <i class="align-self-center mr-3 text-advert-action">
                            Количество показов:&nbsp;<span class="red"><?= $model->clickCount; ?></span>
                        </i>|
<!--                        <i class="align-self-center mr-3" style="padding-right: 10px; font-weight: bold;">-->
<!--                            --><?php //= Html::a('Редактировать', \Yii::$app->request->BaseUrl.'/partners/text-advert/edit/'.$model->id, []); ?>
<!--                        </i>-->
                        <i class="align-self-center mr-3 text-advert-action blue">
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
