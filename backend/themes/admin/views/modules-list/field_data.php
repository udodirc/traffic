<?php
use yii\helpers\Html;

if(!empty($data)):	
	foreach($data as $key => $fiedldata):
?>
	<div id="field_data">	
		<label class="control-label" for="field_data"><?= Yii::t('form', $data[$key]['label']) ?></label>
		<?php
		switch ($data[$key]['type']) 
		{
			case 'text':
				echo HTML::input($data[$key]['type'], $data[$key]['name'].'['.$id.']', '');
			break;
			
			case 'radio':
				echo Html::radioList('required'.'['.$id.']', null, [1 => Yii::t('form', 'Да'), 2 => Yii::t('form', 'Нет')]);
			break;
		}
		?>
	</div>
<?php 
	endforeach; 
endif;
?>

