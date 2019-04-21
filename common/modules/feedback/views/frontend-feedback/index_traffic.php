<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="contacts" class="container form-section">
	<div class="row">
        <div class="col-lg-10">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div class="row">
						<h3 class="m-t-none m-b"><?= $this->title; ?></h3>
						<?php if($feedbackList->totalCount > 0): ?>
							<?= ListView::widget([
								'dataProvider' => $feedbackList,
								'options' => [
									'id' => false,
								],
								'itemOptions' => [
									'tag' => false,
								],
								'layout' => "{pager}\n{items}\n",
								'itemView' => function ($model, $key, $index, $widget) {
									return $this->render('partial/_feedback_list_item',['model' => $model]);
								},
								'pager' => [
									'maxButtonCount' => 10,
								],
							]);
							?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>
