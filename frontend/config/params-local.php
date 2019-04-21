<?php
Yii::setAlias('@frontend_theme', 'frontend_neon');
Yii::setAlias('@backoffice_theme', 'backoffice-hilite/assets');
Yii::setAlias('@backoffice_images', 'frontend/themes/'.Yii::getAlias('@backoffice_theme').'/images');
Yii::setAlias('@backoffice_css', 'frontend/themes/'.Yii::getAlias('@backoffice_theme').'/css');
Yii::setAlias('@frontend_upload', 'frontend/themes/frontend/upload');
return [
];

