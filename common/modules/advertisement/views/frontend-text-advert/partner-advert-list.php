<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\tickets\models\SearchTickets */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success">
			<strong><?= Html::encode(Yii::$app->session->getFlash('success')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger">
			<strong><?= Html::encode(Yii::$app->session->getFlash('error')); ?></strong>
		</div>
	</div>
</div><!-- /.flash-success -->
<?php endif; ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Информация'); ?></div>
				<div class="panel-options"></div>
			</div>
			<div class="panel-body">
				<?= (isset($content) && $content != null) ? $content->content : ''; ?>      
			</div>
		</div>
	</div>
</div>
<!--<div class="row">-->
<!--    <div class="col-12 grid-margin stretch-card">-->
<!--        <div class="card">-->
<!--            <div class="card-body" style="padding: 20px;">-->
<!--                --><?php //$form = ActiveForm::begin([
//					'action' => ['/partners/partner-text-advert'],
//					'method' => 'post',
//					'options' => ['class' => 'forms-sample'],
//				]); ?>
<!--                <div class="form-group row">-->
<!--                    <label for="top-leader-month" class="col-sm-3 col-form-label">--><?php //= Yii::t('form', 'Заголовок'); ?><!--</label>-->
<!--                    <div class="col-sm-9">-->
<!--						--><?php //= $form->field($searchModel, 'title', [])->textInput(); ?>
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="form-group row">-->
<!--                    <label for="top-leader-month" class="col-sm-3 col-form-label">--><?php //= Yii::t('form', 'Ссылка'); ?><!--</label>-->
<!--                    <div class="col-sm-9">-->
<!--			            --><?php //= $form->field($searchModel, 'link', [])->textInput(); ?>
<!--                    </div>-->
<!--                </div>-->
<!--                <button type="submit" class="btn btn-primary mr-2">Поиск</button>-->
<!--				--><?php //ActiveForm::end(); ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::encode($this->title); ?></div>
				<div class="panel-options"></div>
			</div>
			<div class="panel-body" align="right">
                <?= Html::a(Yii::t('form', 'Создать'), \Yii::$app->request->BaseUrl.'/partners/text-advert/create', ['class' => 'btn btn-success']) ?>
            </div>
			<!-- panel body -->
			<div class="panel-body text-advert">
			<?= ListView::widget([
				'dataProvider' => $dataProvider,
				'options' => [],
				'layout' => "{pager}\n{items}\n",
				'itemView' => function ($model, $key, $index, $widget) use ($user) {
					return $this->render('partial/_advert_list_item'.(($user) ? '_user' : ''),['model' => $model]);
				},
				'pager' => [
					'maxButtonCount' => 10,
				],
			]);
			?>  
			</div>
		</div>
	</div>
</div>
