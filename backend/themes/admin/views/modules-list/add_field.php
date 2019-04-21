<?php
use yii\helpers\Html;

?>
<div id="module_custom_field_<?= $id ?>" class="module_custom_field">
	<div id="field_name">
		<label class="control-label" for="field_name"><?= Yii::t('form', 'Название поля в таблице базы (латинскими символами)') ?></label>
		<?= HTML::input('text', 'field_name['.$id.']', '') ?>
	</div>
	<div id="field_type_selector">
		<label class="control-label" for="field_type"><?= Yii::t('form', 'Тип поля') ?></label>
		<?= Html::dropDownList('field_type['.$id.']', null, $data, ['id'=>'field_type', 'prompt'=>Yii::t('form', 'Выберите данные')]) ?>
	</div>
	<div id="field_data"></div>
</div>
