<?php
declare(strict_types=1);

class Estudiante extends Model
{
    protected static function table(): string
    {
        return 'estudiantes';
    }

    protected static function fillable(): array
    {
        return ['usuario_id', 'nombre', 'telefono'];
    }

    public static function findByUsuarioId(int $usuarioId): ?array
    {
        $stmt = self::query('SELECT * FROM estudiantes WHERE usuario_id = :usuario_id LIMIT 1', ['usuario_id' => $usuarioId]);
        return $stmt->fetch() ?: null;
    }

    public static function profile(int $id): ?array
    {
        $sql = 'SELECT e.*, u.username, u.email, u.role
                FROM estudiantes e
                JOIN usuarios u ON e.usuario_id = u.id
                WHERE e.id = :id LIMIT 1';

        $stmt = self::query($sql, ['id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
