<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="col-md-12">
                    <div class="card-body">
						<h4 class="card-title"><?= Yii::t('form', 'Информация'); ?></h4>
						<?= (isset($content) && $content != null) ? $content->content : ''; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php foreach($structures_list as $number=>$data): ?>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="row">
				<div class="col-md-6">
                    <div class="card-body">
						<h4 class="card-title"><?= Yii::t('form', 'Площадки').'&nbsp;-&nbsp;'.Yii::t('form', 'ожидаемый'); ?></h4>
						<?= $this->render('partial/matrices'.$theme, ['matrices' => $model['demo_matrix_'.$number], 'structure' => $number, 'id' => $id, 'demo' => true, 'list_view_count' => $list_view_count, 'levels' => ((isset($matrices_settings_list[$number])) ? $matrices_settings_list[$number] : [])]); ?>
					</div>
				</div>
				<div class="col-md-6">
                    <div class="card-body">
						<h4 class="card-title"><?= Yii::t('form', 'Площадки').'&nbsp;-&nbsp;'.Yii::t('form', 'реальный'); ?></h4>
						<?= $this->render('partial/matrices'.$theme, ['matrices' => $model['matrix_'.$number], 'structure' => $number, 'id' => $id, 'demo' => false, 'list_view_count' => $list_view_count, 'levels' => ((isset($matrices_settings_list[$number])) ? $matrices_settings_list[$number] : [])]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>
