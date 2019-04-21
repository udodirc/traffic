<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (!is_null($model)) ? $model->login : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура партнера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/*echo '<pre>';
print_r($data);
echo '</pre>';
/*
echo strtotime('2016-06-15 22:47:30');*/
?>
<div class="item-content">
	<?= ($demo) ? (isset($content)) ? $content->content : '' : '';?>
</div>
<div class="structure">
	<div class="structure-head">
		<h1><?= Yii::t('form', 'Структура партнеров') ?></h1>
	</div>
	<? if($admin): ?>
	<div class="sponsor-structure-wrap">
		<div class="sponsor-structure">
			<?= Html::a(Yii::t('form', ($demo) ? 'Реальная структура' : 'Демо структура'), [(($demo) ? '' : 'demo-').'structure?id='.$id], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<? endif; ?>
	<div class="structure-wrap">
		<?php
		if(!empty($data)):
			foreach($data as $number=>$matrixData):
			?>
			<div class="matrix">
				<div class="matrix-head">
					<h3><?= Yii::t('form', 'Матрица ').$number; ?></h3>
				</div>
				<div class="matrix-block">
				<?php
				if(!is_null($model)):
					if($number == 1):
						echo $this->render('partial/linear_matrix', ['matrixData' => $matrixData, 'matrixNumber' => $number, 'id' => $id, 'admin' => $admin, 'demo' => $demo]);
					else:
						echo $this->render('partial/binary_matrix', ['matrixData' => $matrixData, 'model' => $model, 'matrixNumber' => $number, 'id' => $id, 'admin' => $admin, 'demo' => $demo]);
					endif;
				endif;
				?>
				</div>
			</div>
			<?php
			endforeach;
		endif;
		?>
	</div>
</div>
