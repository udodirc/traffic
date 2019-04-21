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
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Информация'); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
			<?= (isset($content) && $content != null) ? $content->content : ''; ?>
			</div>
		</div>
	</div>
</div>
<?php foreach($structures_list as $number=>$data): ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Заработок').' - '.Yii::t('form', $data); ?></div>
				<div class="panel-options"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Площадки').'&nbsp;-&nbsp;'.Yii::t('form', 'ожидаемый'); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= $this->render('partial/matrices', ['matrices' => $model['demo_matrix_'.$number], 'structure' => $number, 'id' => $id, 'demo' => true, 'list_view_count' => $list_view_count, 'levels' => ((isset($matrices_settings_list[$number])) ? $matrices_settings_list[$number] : [])]); ?>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title"><?= Yii::t('form', 'Площадки').'&nbsp;-&nbsp;'.Yii::t('form', 'реальный'); ?></div>
				<div class="panel-options"></div>
			</div>
			<!-- panel body -->
			<div class="panel-body">
				<?= $this->render('partial/matrices', ['matrices' => $model['matrix_'.$number], 'structure' => $number, 'id' => $id, 'demo' => false, 'list_view_count' => $list_view_count, 'levels' => ((isset($matrices_settings_list[$number])) ? $matrices_settings_list[$number] : [])]); ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>
