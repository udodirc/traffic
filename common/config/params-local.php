<?php
$rootDir = true;
Yii::setAlias('@root_path', $_SERVER['DOCUMENT_ROOT']);
Yii::setAlias('@root_dir', 'traffic.local');
Yii::setAlias('@dashboard_theme', 'gentella');
Yii::setAlias('@frontend_theme', 'frontend_neon');
Yii::setAlias('@uploads_dir', Yii::getAlias('@root_path').(($rootDir) ? DIRECTORY_SEPARATOR.Yii::getAlias('@root_dir') : '').DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'uploads');
Yii::setAlias('@upload_dir', (($rootDir) ? DIRECTORY_SEPARATOR.Yii::getAlias('@root_dir') : '').DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'uploads');
Yii::setAlias('@content_uploads_dir', Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.'content');
Yii::setAlias('@content_uploads_dir_no_root', Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.'content');
Yii::setAlias('@backend_themes', (($rootDir) ? Yii::getAlias('@root_dir') : '').DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.Yii::getAlias('@dashboard_theme'));
Yii::setAlias('@dashboard_images', (($rootDir) ? DIRECTORY_SEPARATOR : '').Yii::getAlias('@backend_themes').DIRECTORY_SEPARATOR.'images');
Yii::setAlias('@frontend_themes', (($rootDir) ? DIRECTORY_SEPARATOR.Yii::getAlias('@root_dir') : '').DIRECTORY_SEPARATOR.'frontend'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.Yii::getAlias('@frontend_theme'));
Yii::setAlias('@frontend_images', Yii::getAlias('@frontend_themes').DIRECTORY_SEPARATOR.'images');
Yii::setAlias('@frontend_upload', Yii::getAlias('@frontend_themes').DIRECTORY_SEPARATOR.'upload');
Yii::setAlias('@modules', (($rootDir) ? Yii::getAlias('@root_dir') : '').DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'modules');


Yii::setAlias('@admin_uploads', 'admin/uploads');
Yii::setAlias('@backend_uploads', realpath(dirname(__FILE__).'/../../admin'));
Yii::setAlias('@backend_upload_dir', realpath(dirname(__FILE__).'/../../admin/uploads'));
Yii::setAlias('@uploads', '../../../admin/uploads');
Yii::setAlias('@uploads2', '../admin/uploads');
Yii::setAlias('@payments_csv', realpath(dirname(__FILE__).'/../../admin/uploads/csv'));
Yii::setAlias('@landings', realpath(dirname(__FILE__).'/../../admin/uploads/landings'));
Yii::setAlias('@backend_upload_banner_dir', 'admin/uploads/banners');
Yii::setAlias('@backend_admin_js_dir', 'backend/themes/admin/js');
Yii::setAlias('@backend_web_uploads', realpath(dirname(__FILE__).'/../../admin'));
Yii::setAlias('@backend_root', realpath(__DIR__ . '/../../admin'));
Yii::setAlias('@common_uploads', realpath(dirname(__FILE__).'/../../common/web'));
Yii::setAlias('@frontend_uploads', 'frontend/uploads');
Yii::setAlias('@banners_uploads', '/../../admin/uploads/banners');
Yii::setAlias('@slider_uploads', '/../../admin/uploads/slider');
Yii::setAlias('@banners_advert', realpath(dirname(__FILE__).'/../../admin/uploads/banners'));
Yii::setAlias('@sponsor_advert', realpath(dirname(__FILE__).'/../../admin/uploads/sponsor_advert'));
Yii::setAlias('@sponsor_advert_view', 'admin/uploads/sponsor_advert');
Yii::setAlias('@slider', realpath(dirname(__FILE__).'/../../admin/uploads/slider'));
Yii::setAlias('@frontend_images', 'frontend/themes/frontend-landx/assets/images');
Yii::setAlias('@backoffice_images', 'frontend/themes/backoffice/images');
Yii::setAlias('@root_dir', '../../../');
Yii::setAlias('@root_dir2', '../');
Yii::setAlias('@upload_dir', 'uploads');
Yii::setAlias('@assets', realpath(__DIR__ . '/assets'));
Yii::setAlias('@uploads-assets', realpath(__DIR__ . 'common/modules/uploads/assets'));
Yii::setAlias('@landings-assets', realpath(dirname(__FILE__).'/../../admin/uploads/landings/'));
Yii::setAlias('@content', realpath(dirname(__FILE__).'/../../admin/uploads/content/content'));
Yii::setAlias('@content_uploads', '/../../admin/uploads/content/content');
return [
];
