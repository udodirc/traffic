<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class CreateUploadFolderController extends Controller
{
    /**
     * Создает папки для admin/uploads с правами 0777
     * php yii create-upload-folder/init
     */
    public function actionInit()
    {
        // Список папок (используем реальные пути или алиасы, которые реально существуют)
        $folders = [
            Yii::getAlias('@app') . '/admin/uploads',
            Yii::getAlias('@app') . '/admin/uploads/content',
            Yii::getAlias('@app') . '/admin/uploads/banners',
            Yii::getAlias('@app') . '/admin/uploads/slider',
            Yii::getAlias('@app') . '/admin/uploads/sponsor_advert',
            Yii::getAlias('@app') . '/admin/uploads/landings',
        ];

        foreach ($folders as $path) {
            if (!is_dir($path)) {
                if (mkdir($path, 0777, true)) {
                    echo "Папка создана: $path\n";
                } else {
                    echo "Ошибка создания папки: $path\n";
                    continue;
                }
            } else {
                echo "Папка уже существует: $path\n";
            }

            if (!chmod($path, 0777)) {
                echo "Не удалось установить права на $path\n";
            } else {
                echo "Права установлены: 0777 для $path\n";
            }
        }

        echo "Инициализация папок завершена.\n";
        return 0;
    }
}