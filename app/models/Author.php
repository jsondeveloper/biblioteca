<?php
declare(strict_types=1);

class Author extends Model
{
    protected static function table(): string
    {
        return 'libros';
    }

    public static function all(): array
    {
        $stmt = self::db()->query(
            'SELECT MIN(id) AS id, autor AS name, NULL AS biography
             FROM libros
             GROUP BY autor
             ORDER BY autor'
        );

        return $stmt->fetchAll();
    }
}
