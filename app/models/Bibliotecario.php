<?php
declare(strict_types=1);

class Bibliotecario extends Model
{
    protected static function table(): string
    {
        return 'bibliotecarios';
    }

    protected static function fillable(): array
    {
        return ['usuario_id', 'nombre'];
    }

    public static function findByUsuarioId(int $usuarioId): ?array
    {
        $stmt = self::query('SELECT * FROM bibliotecarios WHERE usuario_id = :usuario_id LIMIT 1', ['usuario_id' => $usuarioId]);
        return $stmt->fetch() ?: null;
    }
}
