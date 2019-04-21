<?php
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (isset($this->params['title'])) ? $this->params['title'] : '';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$width = 100 / count($structures_list);
?>
<div class="structure">
	<div class="structure_block">
		<div class="structure-head">
			<h1><?= Yii::t('form', 'Структура партнеров') ?></h1>
		</div>
		<div class="structure_block">
		<div class="structure-head" align="center">
			<h1 class="bold"><?= Yii::t('form', 'Заработок') ?></h1>
		</div>
		<?php foreach($structures_list as $number=>$data): ?>
		<div class="structure-wrap">
			<div class="structure-block">
				<div class="width_center">
					<div class="structure-head">
						<h2 class="structure-block-head"><?= $data.' - '.Yii::t('form', 'Демо') ?></h2>
					</div>
					<div class="width_<?= $width; ?>">
						<div class="structure-head">
							<h3 class="bold"><?= Yii::t('form', 'Матрицы') ?></h3>
						</div>
						<?= $this->render('partial/matrices', ['matrices' => $model['demo_matrix_'.$number], 'structure' => $number, 'id' => $id, 'demo' => true, 'list_view_count' => $list_view_count, 'levels' => ((isset($matrices_settings_list[$number])) ? $matrices_settings_list[$number] : [])]); ?>
					</div>
				</div>
			</div>
			<div class="structure-block">
				<div class="width_center">
					<div class="structure-head">
						<h2 class="structure-block-head"><?= $data.' - '.Yii::t('form', 'Реальная структура') ?></h2>
					</div>
					<div class="width_<?= $width; ?>">
						<div class="structure-head">
							<h3 class="bold"><?= Yii::t('form', 'Матрицы') ?></h3>
						</div>
						<?= $this->render('partial/matrices', ['matrices' => $model['matrix_'.$number], 'structure' => $number, 'id' => $id, 'demo' => false, 'list_view_count' => $list_view_count, 'levels' => ((isset($matrices_settings_list[$number])) ? $matrices_settings_list[$number] : [])]); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
