<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class PartnersController extends Controller
{
    /**
     * Импорт дампа партнёров.
     *
     * Пример запуска:
     * php yii partners/import
     */
    public function actionImport()
    {
        $dumpPath = Yii::getAlias('@app/../docker/mysql/partners_dump.sql');

        if (!file_exists($dumpPath)) {
            $this->stderr("❌ Файл дампа не найден: {$dumpPath}\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $db = Yii::$app->db;
        $dsn = $db->dsn;

        // Извлекаем имя БД из DSN
        preg_match('/dbname=([^;]+)/', $dsn, $matches);
        $dbName = $matches[1] ?? null;

        if (!$dbName) {
            $this->stderr("Не удалось определить имя БД из DSN\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user = $db->username;
        $pass = $db->password;

        $cmd = "mysql -u{$user} -p{$pass} {$dbName} < {$dumpPath}";
        $this->stdout("🔄 Импорт дампа...\n");

        $result = shell_exec($cmd);

        if ($result === null) {
            $this->stderr("Ошибка при выполнении импорта.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("✅ Импорт успешно завершён!\n", Console::FG_GREEN);
        return ExitCode::OK;
    }
}