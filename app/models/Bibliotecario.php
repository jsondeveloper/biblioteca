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
        return ['usuario_id', 'numero_empleado', 'nombre'];
    }

    public static function findByNumeroEmpleado(string $numeroEmpleado): ?array
    {
        $stmt = self::query('SELECT * FROM bibliotecarios WHERE numero_empleado = :numero_empleado LIMIT 1', ['numero_empleado' => $numeroEmpleado]);
        return $stmt->fetch() ?: null;
    }

    public static function findByUsuarioId(int $usuarioId): ?array
    {
        $stmt = self::query('SELECT * FROM bibliotecarios WHERE usuario_id = :usuario_id LIMIT 1', ['usuario_id' => $usuarioId]);
        return $stmt->fetch() ?: null;
    }
}
