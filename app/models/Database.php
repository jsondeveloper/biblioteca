<?php
declare(strict_types=1);

class Database
{
    public static function connect(): PDO
    {
        static $pdo = null;

        if ($pdo instanceof PDO) {
            return $pdo;
        }

        $config = require BASE_PATH . '/config/database.php';
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['dbname'],
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            $pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Error de conexión a la base de datos: ' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        return $pdo;
    }
}
