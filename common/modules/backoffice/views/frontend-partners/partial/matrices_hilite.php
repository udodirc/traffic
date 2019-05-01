<?php
use yii\helpers\Html;

if($matrices > 0):
?>
	<ul class="levels">
	<?php for($i=1; $i<=$matrices; $i++): 
	$level = (isset($levels[$i])) ? $levels[$i] : 0;
	?>
		<li>
			<?= Html::a('
			<div class="left-element">
				<i class="glyphicon glyphicon-th-list"></i>
				<span class="title">'.Yii::t('form', 'Площадка').'&nbsp;№'.$i.'</span>
			</div>', ['/partners/partners-matrix/'.$id.'/'.$structure.'/'.$i.'/'.(($demo) ? 1 : 0).'/'.(($level['levels'] > $list_view_count) ? 1 : 0)]); 
			?>
		</li>
	<?php endfor; ?>
	</ul>
<?php
endif;
?>
