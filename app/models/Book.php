<?php
declare(strict_types=1);

class Libro extends Model
{
    protected static function table(): string
    {
        return 'libros';
    }

    protected static function fillable(): array
    {
        return ['titulo', 'isbn', 'autor', 'categoria_id', 'estado', 'anio_publicacion', 'descripcion'];
    }

    public static function search(string $term): array
    {
        $sql = 'SELECT l.*, c.nombre AS categoria
                FROM libros l
                JOIN categorias c ON l.categoria_id = c.id
                WHERE l.titulo LIKE :term OR l.autor LIKE :term OR l.isbn LIKE :term
                ORDER BY l.titulo';

        return self::query($sql, ['term' => '%' . $term . '%'])->fetchAll();
    }

    public static function getByEstado(string $estado): array
    {
        return self::query('SELECT * FROM libros WHERE estado = :estado ORDER BY titulo', ['estado' => $estado])->fetchAll();
    }

    public static function findByISBN(string $isbn): ?array
    {
        $stmt = self::query('SELECT * FROM libros WHERE isbn = :isbn LIMIT 1', ['isbn' => $isbn]);
        return $stmt->fetch() ?: null;
    }

    public static function setStatus(int $id, string $estado): bool
    {
        $estadosValidos = ['Disponible', 'Reservado', 'Prestado', 'Mantenimiento'];
        if (!in_array($estado, $estadosValidos, true)) {
            throw new InvalidArgumentException('Estado de libro no válido.');
        }

        return self::update($id, ['estado' => $estado]);
    }

    public static function reserve(int $id): bool
    {
        return self::setStatus($id, 'Reservado');
    }

    public static function lend(int $id): bool
    {
        return self::setStatus($id, 'Prestado');
    }

    public static function release(int $id): bool
    {
        return self::setStatus($id, 'Disponible');
    }

    public static function maintenance(int $id): bool
    {
        return self::setStatus($id, 'Mantenimiento');
    }
}

class Book extends Libro
{
}
