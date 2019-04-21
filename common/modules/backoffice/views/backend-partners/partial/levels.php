<?php
use yii\helpers\Html;

if(!empty($levels)):
?>
	<ul>
	<?php foreach($levels as $level): ?>
		<li>
			<div class="panel-wrapper fixed">
				<div class="panel level">
					<?= Html::a('<div class="count">'.$level->level.'&nbsp;'.Yii::t('form', 'Уровень').'</div>', ['/backoffice/backend-partners/partners-level/'.$id.'/'.$level->level.'/'.(($demo) ? 1 : 0).'/'.(($credit) ? 1 : 0)]); ?>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
<?php
endif;
?>
