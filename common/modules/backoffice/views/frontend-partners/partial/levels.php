<?php
use yii\helpers\Html;

if(!empty($levels)):
?>
	<ul class="levels">
	<?php foreach($levels as $level): ?>
		<li>
			<?= Html::a('
			<div class="left-element">
				<i class="glyphicon glyphicon-th-list"></i>
				<span class="title">'.$level->level.'&nbsp;'.Yii::t('form', 'Уровень').'</span>
			</div>', ['/partners/partners-level/'.$id.'/'.$level->level.'/'.(($demo) ? 1 : 0).'/'.(($credit) ? 1 : 0)]); 
			?>
		</li>
	<?php endforeach; ?>
	</ul>
<?php
endif;
?>
