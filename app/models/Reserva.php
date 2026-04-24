<?php
declare(strict_types=1);

class Reserva extends Model
{
    protected static function table(): string
    {
        return 'reservas';
    }

    protected static function fillable(): array
    {
        return ['libro_id', 'estudiante_id', 'fecha_reserva', 'fecha_expiracion', 'estado'];
    }

    public static function reserveBook(int $libroId, int $estudianteId, string $fechaExpiracion): int
    {
        $data = [
            'libro_id' => $libroId,
            'estudiante_id' => $estudianteId,
            'fecha_reserva' => date('Y-m-d'),
            'fecha_expiracion' => $fechaExpiracion,
            'estado' => 'Activa',
        ];

        return self::create($data);
    }

    public static function cancel(int $id): bool
    {
        return self::update($id, ['estado' => 'Cancelada']);
    }

    public static function markAsFulfilled(int $id): bool
    {
        return self::update($id, ['estado' => 'Cumplida']);
    }

    public static function activeByBook(int $libroId): array
    {
        return self::query('SELECT * FROM reservas WHERE libro_id = :libro_id AND estado = :estado ORDER BY fecha_reserva DESC', [
            'libro_id' => $libroId,
            'estado' => 'Activa',
        ])->fetchAll();
    }
}
