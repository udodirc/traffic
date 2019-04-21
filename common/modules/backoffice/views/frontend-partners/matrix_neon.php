<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Html::encode($this->title); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
			<?php
			if(!empty($data)):
				foreach($data as $i=>$matrixData):
				?>
				<div class="matrix">
					<div class="matrix-head">
						<h3><?= Yii::t('form', 'ID площадки').' - '.$i; ?></h3>
					</div>
					<div class="matrix-block">
					<?php
					if(!is_null($model)):
						if($list_view):
							echo $this->render('partial/list_matrix', ['matrixData' => $matrixData, 'structureNumber' => $structure_number, 'matrixNumber' => $number, 'demo' => $demo, 'matrixID' => $i, 'list_view' => $list_view, 'pay_off' => $pay_off, 'matrix_wide' => $matrix_wide]);
						else:
							echo $this->render('partial/binary_matrix', ['matrixData' => $matrixData, 'matrixID' => $i, 'model' => $model, 'matrixNumber' => $number, 'id' => $id, 'admin' => $admin, 'demo' => $demo, 'gold_token' => (isset($gold_token_list[$i]) ? $gold_token_list[$i] : 0)]);
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
	</div>
</div>
