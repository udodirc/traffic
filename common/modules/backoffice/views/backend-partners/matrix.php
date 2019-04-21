<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (!is_null($model)) ? $model->login : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура партнера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
			foreach($data as $i=>$matrixData):
			?>
			<div class="matrix">
				<div class="matrix-head">
					<h2><?= Yii::t('form', 'Матрица').'&nbsp;-&nbsp;'.$number; ?></h2>
				</div>
				<div class="matrix-head">
					<h3><?= Yii::t('form', 'ID Матрицы').'&nbsp;-&nbsp;'.$i; ?></h3>
				</div>
				<div class="matrix-block">
				<?php
				if(!is_null($model)):
					if($matrix_type == 1):
						echo $this->render('partial/linear_matrix', ['matrixData' => $matrixData, 'matrixID' => $i, 'structureNumber' => $structure_number, 'matrixNumber' => $number, 'id' => $id, 'admin' => $admin, 'demo' => $demo]);
					else:
						if($list_view):
							echo $this->render('partial/list_matrix', ['matrixData' => $matrixData, 'structureNumber' => $structure_number, 'matrixNumber' => $number, 'demo' => $demo, 'matrixID' => $i, 'list_view' => $list_view, 'pay_off' => $pay_off, 'matrix_wide' => $matrix_wide]);
						else:
							echo $this->render('partial/binary_matrix', ['matrixData' => $matrixData, 'matrixID' => $i, 'model' => $model, 'structureNumber' => $structure_number, 'matrixNumber' => $number, 'list_view' => $list_view, 'id' => $id, 'admin' => $admin, 'demo' => $demo, 'gold_token' => (isset($gold_token_list[$i]) ? $gold_token_list[$i] : 0)]);
						endif;
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
