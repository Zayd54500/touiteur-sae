<?php
namespace Zayd54500\TouiteurSae\Database;
use PDO;

class DatabaseConnection {
    public static function getConfig(): array {
        $config = parse_ini_file(__DIR__ . '/../../config/database.ini');
        if ($config === false) {
            throw new \RuntimeException("Impossible de lire la config");
        }

        return $config;
    }

    public static function getConnection(): PDO {
        $config = self::getConfig();
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
        return new PDO (
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
}
?>