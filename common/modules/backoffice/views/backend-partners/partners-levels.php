<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = (!is_null($model)) ? $model->login : '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('form', 'Структура партнера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="structure">
	<div class="structure-head">
		<h1><?= Yii::t('form', 'Структура партнеров') ?></h1>
	</div>
	<div class="sponsor-structure-wrap">
		<div class="sponsor-structure">
			<?= Html::a(Yii::t('form', ($demo) ? 'Реальная структура' : 'Демо структура'), [(($demo) ? '' : 'demo-').'structure?id='.$id], ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<div class="structure-wrap">
		<?php
		if(!empty($levels)):
		?>
			<?php for($i=1; $i<=$levels; $i++): ?>
			<div class="panel-wrapper fixed">
				<div class="panel level">
					<?= Html::a('<div class="count">'.$i.'&nbsp;'.Yii::t('form', 'Уровень').'</div>', ['/backoffice/backend-partners/partners-level/'.$id.'/'.$i.'/'.(($demo) ? 1 : 0)]); ?>
				</div>
			</div>
			<?php endfor; ?>
		<?php endif; ?>
	</div>
</div>
