<?php

declare(strict_types=1);

class BaseController
{
    protected function render(string $view, array $params = []): void
    {
        extract($params, EXTR_SKIP);

        $viewFile = BASE_PATH . '/app/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new RuntimeException("Vista no encontrada: $viewFile");
        }

        require BASE_PATH . '/app/views/layout/header.php';
        require $viewFile;
        require BASE_PATH . '/app/views/layout/footer.php';
    }

    protected static function db(): PDO
    {
        return Database::connect();
    }

    protected static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}
