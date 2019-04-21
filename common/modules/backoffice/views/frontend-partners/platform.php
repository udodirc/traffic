<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('form', 'Площадка №').$number;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?= Html::encode($this->title); ?></h5>
				<span class="right">
					<?= Html::a(Yii::t('form', 'Назад'), ['/partners/matrices-structure/'.(($demo) ? '1' : '0')], ['class' => 'btn btn-success']) ?>
				</span>
			</div>
            <div class="ibox-content">
				<div class="structure-wrap">
					<?php
					if(!empty($data)):
						foreach($data as $i=>$matrixData):
						?>
						<div class="matrix">
							<div class="matrix-head">
								<h3><?= Yii::t('form', 'Матрица ').$i; ?></h3>
							</div>
							<div class="matrix-block">
							<?php
							if(!is_null($model)):
								if($number == 1):
									echo $this->render('partial/linear_matrix', ['matrixData' => $matrixData, 'matrixNumber' => $i, 'id' => $id, 'admin' => $admin, 'demo' => $demo]);
								else:
									echo $this->render('partial/binary_matrix', ['matrixData' => $matrixData, 'model' => $model, 'matrixNumber' => $i, 'id' => $id, 'admin' => $admin, 'demo' => $demo]);
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
</div>
