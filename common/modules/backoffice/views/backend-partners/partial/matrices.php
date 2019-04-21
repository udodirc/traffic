<?php
use yii\helpers\Html;

if($matrices > 0):
?>
	<ul>
	<?php for($i=1; $i<=$matrices; $i++): 
	$level = (isset($levels[$i])) ? $levels[$i] : 0;
	?>
		<li>
			<div class="panel-wrapper fixed">
				<div class="panel level">
					<?= Html::a('<div class="count">'.Yii::t('form', 'Площадка').'&nbsp;'.$i.'</div>', ['/backoffice/backend-partners/partners-matrix/'.$id.'/'.$structure.'/'.$i.'/'.(($demo) ? 1 : 0).'/'.(($level['levels'] > $list_view_count) ? 1 : 0)]); ?>
				</div>
			</div>
		</li>
	<?php endfor; ?>
	</ul>
<?php
endif;
?>
